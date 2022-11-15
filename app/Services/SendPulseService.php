<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;


use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;


class SendPulseService
{

    private $base_url, $secretKey, $user_id, $client, $grid_id;
    private ApiClient $api;

    public function __construct()
    {
        $this->base_url = "https://api.sendpulse.com/";
        $this->user_id = '54f351247d456d5d90b7ae523466ca29';
        $this->secretKey = '0e7a1c5834807bfb57558ca6d8971535';
        $this->grid_id = 329495;
        $this->api = new ApiClient($this->user_id, $this->secretKey, new FileStorage());
    }

    public static function api()
    {
        $user_id = '54f351247d456d5d90b7ae523466ca29';
        $secretKey = '0e7a1c5834807bfb57558ca6d8971535';
        return new ApiClient($user_id, $secretKey, new FileStorage());
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

    public function mailingList()
    {
        return $this->api->listAddressBooks();
    }


    public function validateEmail($email)
    {
        list($user, $domain) = explode('@', $email);
        if (!checkdnsrr($domain, 'MX')) return false;
        $api_key = "8406e52d93f44f91a400d21f7895a976";
        $client = new Client();
        $response = $client->request('GET', 'https://emailvalidation.abstractapi.com/v1/?api_key=' . $api_key . '&email=' . $email);
        if ($response->getStatusCode()) {
            $data = (array)json_decode($response->getBody(), true);
            if ($data["deliverability"] === "DELIVERABLE") return true;
        }
        return false;

    }

    public function contactList()
    {

    }

    /**
     * @param $user
     * @return mixed
     */
    public function addContact($user): mixed
    {
        if ($this->validateEmail($user->email)) {
            return $this->api->addEmails($this->grid_id, [['email' => $user->email, 'variables' => ['first_name' => $user->first_name, 'last_name' => $user->last_name, 'country' => $user->country]]]);
        }
        return null;
    }

}
