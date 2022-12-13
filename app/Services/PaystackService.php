<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class PaystackService
{

    private $base_url, $secretKey, $publicKey, $merchant_email, $client, $amount = 0, $orderId, $metadata = ['custom_fields' => []];

    public function __construct()
    {
        $this->base_url = env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co');
        $this->secretKey = env('PAYSTACK_SECRET_KEY');
        $this->merchant_email = env('MERCHANT_EMAIL');
        $this->publicKey = env('PAYSTACK_PUBLIC_KEY');
        $this->setClient();
    }

    /**
     * Sets the client header
     */
    private function setClient()
    {
        $authBearer = 'Bearer ' . $this->secretKey;
        $this->client = new Client(
            [
                'base_uri' => $this->base_url,
                'headers' => [
                    'Authorization' => $authBearer,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount = 0)
    {
        $this->amount = $amount * 100;
        return $this;
    }

    /**
     * @param float $amount
     */
    public function addMetaField(string $key = '', string $value = '')
    {
        $this->metadata['custom_fields'][] = ['display_name' => $key, 'variable_name' => Str::slug($key, '_'), 'value' => $value];
        return $this;
    }

    public function addMeta(string $key = '', string $value = '')
    {
        $this->metadata[$key] = $value;
        return $this;
    }


    public static function transactionCharge($amount)
    {
        $ratio = 0.015;
        $settled = $amount < 2500 ? ($amount * $ratio) : (($amount * $ratio) + 100);
        return $settled > 2000 ? 2000 : $settled;
    }

    /**
     * generate Paystack reference code to add Card
     * @return JsonResponse
     */
    public function initiateCardTransaction()
    {
        $user = getUser();
        $body = [
            'reference' => $this->reference,
            "amount" => $this->amount,
            "email" => $user->email,
            'callback_url' => url('/payment/paystack/callback'),
        ];
        $body['metadata'] =  $this->metadata;
        $body['order_id'] =  $this->orderId;
        return (object)$this->makeRequest('/transaction/initialize', 'post', $body);
    }

    /**
     * Finally, generate a hashed token
     * @param integer $length
     * @return string
     */
    public static function getHashedToken($length = 25)
    {
        $token = "";
        $max = strlen(static::getPool());
        for ($i = 0; $i < $length; $i++) {
            $token .= static::getPool()[static::secureCrypt(0, $max)];
        }

        return $token;
    }

    /**
     * Get the pool to use based on the type of prefix hash
     * @param string $type
     * @return string
     */
    private static function getPool($type = 'alnum')
    {
        switch ($type) {
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'hexdec':
                $pool = '0123456789abcdef';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            case 'distinct':
                $pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
                break;
            default:
                $pool = (string)$type;
                break;
        }

        return $pool;
    }

    /**
     * Generate a random secure crypt figure
     * @param integer $min
     * @param integer $max
     * @return integer
     */
    private static function secureCrypt($min, $max)
    {
        $range = $max - $min;

        if ($range < 0) {
            return $min; // not so random...
        }

        $log = log($range, 2);
        $bytes = (int)($log / 8) + 1; // length in bytes
        $bits = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);

        return $min + $rnd;
    }

    /**
     * @param String $url
     * @param String $method
     * @param Object $data
     * @return JsonResponse
     */
    private function makeRequest(string $url, string $method, $data = [])
    {
        $request_url = $this->base_url . $url;
        try {
            $response = $this->client->{strtolower($method)}($request_url, ['body' => json_encode($data)]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        return json_decode($response->getBody(), true);
    }

    public function initializePayment($body = [], $data)
    {
        // $user = getUser();
        $body['reference'] = $data->reference_id;
        // $body['callback_url'] = route('payment.callback');
        $body['amount'] = $body['amount'] ?? $this->amount;
        $body['metadata'] = $body['metadata'] ?? $this->metadata;
        $body['order_id'] = $body['order_id'] ?? $this->orderId;
        $body['email'] = $data->email;
        $response = $this->makeRequest('/transaction/initialize', 'post', $body);
        $url = $response['data']['authorization_url'];
        return redirect($url);
    }


    /**
     * Verifies The transaction and added the card for the user
     */
    public function verifyPayment()
    {
        $request = request();
        $url = '/transaction/verify/' . $request->get('reference');
        return (array)$this->makeRequest($url, 'get');
    }

    public function getPaymentData(): object
    {
        $data = [];
        $response = $this->verifyPayment();
        try {
            if ($response['status']) {
                $data = $response['data'];
            }
        } catch (\Exception $e) {

        }
        return (object)($data);
    }

    /**
     * Verifies The transaction for the user
     * @param $ref
     */
    public function verify($ref)
    {
        $url = '/transaction/verify/' . $ref;
        return $this->makeRequest($url, 'get');
    }

    /**
     * Verifies The transaction and added the card for the user
     * @param Request $request
     * @return JsonResponse
     */
    public function resolveAccountNumber($account, $bank)
    {
        $url = '/bank/resolve?account_number=' . $account . '&bank_code=' . $bank;
        return (object)$this->makeRequest($url, 'get');
    }

    /**
     * Verifies The transaction and added the card for the user
     * @param Request $request
     * @return JsonResponse
     */
    public function resolveBVN(Request $request)
    {
        $url = '/identity/bvn/resolve/' . $request->get('bvn');
        return (object)$this->makeRequest($url, 'get');
    }

    public function listBanks()
    {
        $url = '/bank';
        return (object)$this->makeRequest($url, 'get');
    }

    public function listTransactions($from = null, $to = null)
    {
        $url = '/transaction';
        return (object)$this->makeRequest($url, 'get');
    }

    /**
     * Charge the user with the already added authorization_code
     * @param Int $amount
     * @param String $authorization_code
     * @param User $user
     * @return JsonResponse
     */
    public function chargeCustomer(int $amount, string $authorization_code, object $user)
    {
        $url = '/transaction/charge_authorization';
        $body = [
            'amount' => $amount,
            'authorization_code' => $authorization_code,
            'email' => $user->email,
        ];
        return (object)$this->makeRequest($url, 'post', $body);
    }


    public function updateUserAccount($user)
    {

//        if ($user->payment_id) {
//            $url = '/transferrecipient/' . $user->payment_id;
//            $body = [
//                'type' => 'nuban', 'email' => $user->email,
//                'name' => $user->acct_name,
//                'description' => 'Account Description for' . $user->acct_name,
//                'account_number' => $user->acct_num,
//                'bank_code' => $user->bank_code,
//                'currency' => 'NGN'
//            ];
//            $method = 'put';
//            $response = $this->makeRequest($url, $method, $body);

//        }else{
        $url = '/transferrecipient';
        $method = 'post';
        $body = [
            'type' => 'nuban',
            'name' => $user->acct_name,
            'description' => 'Account Description for' . $user->acct_name,
            'account_number' => $user->acct_num,
            'bank_code' => $user->bank_code,
            'currency' => 'NGN'
        ];
        $response = $this->makeRequest($url, $method, $body);
        if ($response['status']) {
            $user->payment_id = $response['data']['recipient_code'] ?? '';
            $user->save();
        }
//        }
        return $response;

    }

    public function addTransferRecipient($acct_name, $acct_num, $bank_code)
    {
        $url = '/transferrecipient';
        $method = 'post';
        $body = [
            'type' => 'nuban',
            'name' => $acct_name,
            'description' => 'Account Description for' . $acct_name,
            'account_number' => $acct_num,
            'bank_code' => $bank_code,
            'currency' => 'NGN'
        ];
        $response = $this->makeRequest($url, $method, $body);
        $payment_id = '';
        if ($response['status']) {
            $payment_id = $response['data']['recipient_code'] ?? '';
        }
        return $payment_id;
    }

    public function updateTransferRecipient($id, $acct_name, $acct_num, $bank_code)
    {
        $url = "/transferrecipient/$id";
        $method = 'put';
        $body = [
            'type' => 'nuban',
            'name' => $acct_name,
            'description' => 'Account Description for' . $acct_name,
            'account_number' => $acct_num,
            'bank_code' => $bank_code,
            'currency' => 'NGN'
        ];
        $response = $this->makeRequest($url, $method, $body);
        $payment_id = '';
        if ($response['status']) {
            $payment_id = $response['data']['recipient_code'] ?? '';
        }
        return $payment_id;
    }

    public function initiateTransfer($payment_id, $amount, $reason = 'Personnel Payment')
    {
        $url = '/transfer';
        $body = [
            'source' => "balance",
            'amount' => $amount * 100,
            'recipient' => $payment_id,
            'reason' => $reason
        ];
        return $this->makeRequest($url, 'post', $body);
    }

    public function calculateSettlement($amount)
    {
        $ratio = 0.015;
        $settled = $amount < 2500 ? ($amount * $ratio) : (($amount * $ratio) + 100);
        return $settled > 2000 ? 2000 : $settled;
    }

    public function transferValue($amount)
    {
        return ($amount < 5000) ? 10 : (($amount > 50000) ? 50 : 25);
    }

    public function setOrderId(mixed $orderId)
    {
        $this->orderId = $orderId;
    }
}
