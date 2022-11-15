<?php

namespace App\Http\Controllers;


use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\UserVerification;
use App\Notifications\EmailVerificationRequest;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Services\PaystackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{


    private $paystack, $repo;
    private string $api_key = 'GET_GRID_API';

    /**
     * UserController constructor.
     * @param PaystackService $paystackService
     * @param UserRepository $repo
     */
    public function __construct(PaystackService $paystackService, UserRepository $repo)
    {
        $this->paystack = $paystackService;
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    final public function login(Request $request): JsonResponse
    {
        $this->validateData(['email' => 'required|email', 'password' => 'required']);

        $user = User::query()->where('email', $request->email)->first();

        if (!$user)
            return $this->respondWithError('Account not found');

        if (!Hash::check($request->password, $user->password))
            return $this->respondWithError(['message' => 'Invalid credentials']);

        $token = $user->createToken($this->api_key)->accessToken;
        return $this->respondWithSuccess(['data' => ['token' => $token, 'user' => $this->prepareUserData($user)]], 201);
    }

    /**
     * @param User $user
     * @return User
     */
    final public function prepareUserData(User $user): User
    {
        return $this->repo->prepareUserData($user);
    }


    /**
     * Gets Authenticated User
     * @param Request $request
     * @return JsonResponse
     */
    final public function getAuthenticatedUser(Request $request): JsonResponse
    {
        if (!$user = getUser()) return $this->respondWithError('Account not found', 404);
        return $this->respondWithSuccess($this->prepareUserData($user));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    final public function register(Request $request): JsonResponse
    {
        $request->validate(['first_name' => 'required|min:2', 'last_name' => 'required|min:2',
            'email' => 'required|email|unique:users', 'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required'
        ]);

        $user = DB::transaction(function () use ($request) {
            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            // $user->notify(new EmailVerificationRequest());
            return $user;
        });
        $accessToken = $user->createToken($this->api_key)->accessToken;
        return $this->respondWithSuccess(['data' => ['token' => $accessToken, 'user' => $this->repo->prepareUserData($user)]], 201);
    }


    final public function forgetPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email'],
            ['email' => ['email' => 'Enter a valid email address', 'exists' => 'Email record not found']]);
        $user = User::whereEmail($request->email)->first();
        if (!$user) return $this->respondWithError('Account does not match record');
        DB::transaction(function () use ($request, $user) {
            $token = rand(777777, 999999);
            DB::table('password_resets')->insert(['email' => $request->email, 'token' => $token, 'created_at' => now()]);
            $user->notify(new PasswordResetRequest($token));
        });
        return $this->respondWithSuccess('Successfully created a reset token check your email ');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['password' => ['required', 'confirmation', 'min:6'], 'old_password' => ['required']]);
        $user = getUser();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update(['password' => Hash::make($request->password)]);
            $user->notify(new PasswordResetSuccess());
            DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => true]);
            return $this->respondWithSuccess($user);
        }
        return $this->respondWithError('Invalid Old Password');
    }


    public function notifications()
    {
        $user = getUser();
        $notifications = $user->notifications;
        return $this->respondWithSuccess(['data' => $notifications]);
    }


    public function getUserBank()
    {
        if (!$user = getUser()) {
            return response()->json(['error' => 'user_not_found'], 404);
        }
        $bank = [
            'bank_code' => $user->bank_code,
            'account_number' => $user->acct_num,
            'account_name' => $user->acct_name,
            'bank_name' => $user->bank_name,
        ];
        return $this->respondWithSuccess($bank);
    }


    public function getValidateBank(Request $request)
    {
        $request->validate(['acct_num' => 'required|min:10|max:10', 'bank_code' => 'required']);
        $bank = $this->paystack->resolveAccountNumber($request->acct_num, $request->bank_code);
        return $this->respondWithSuccess($bank);
    }


    public function getBankCodes()
    {
        $data = $this->paystack->listBanks();
        return $this->respondWithSuccess($data);
    }

    public function addUserBank(Request $request)
    {
        $request->validate([
            'acct_num' => 'required|min:10|max:10',
            'bank_code' => 'required',
            'bank_name' => 'required',
            'acct_name' => 'required',
        ]);
        $user = getUser();
        $user->bank_code = $request->bank_code;
        $user->acct_num = $request->acct_num;
        $user->bank_name = $request->bank_name;
        $user->acct_name = $request->acct_name;
        $user->save();
        (new PaystackService())->updateUserAccount($user);
        return $this->respondWithSuccess($user);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|file|mimes:jpeg,jpg,bmp,png|max:2048']);
        $user = getUser();
        $user->avatar = moveFile($request->avatar);
        $user->save();
        return $this->respondWithSuccess($this->prepareUserData($user));
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'numeric', 'min:6', 'exists:password_resets,token'],
            'email' => ['required', 'email'],
            'password' => 'required|min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6'
        ], ['otp' => ['exists' => "OTP is not valid"]]);
        $token = DB::table('password_resets')->where('email', $request->email)->where('token', $request->otp)->first();
        if (!$token) return $this->respondWithError('Token not valid');
        $user = User::where('email', $token->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
        $user->notify(new PasswordResetSuccess());
        DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => true]);
        DB::table('password_resets')->where('email', $request->email)->where('token', $request->otp)->delete();
        return $this->respondWithSuccess('Password changed successfully');
    }

    public function changePassword(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'current_password' => ['required', 'min:6',],
            'password' => ['required', 'min:6',],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $user = getUser();
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->respondWithError('Current password is wrong');
        }
        $user->update(['password' => Hash::make($request->password)]);
        $user->notify(new PasswordResetSuccess());
        DB::table('oauth_access_tokens')->where('user_id', $user->id)->update(['revoked' => true]);
        return $this->respondWithSuccess('Password changed successfully');
    }

    public function resendPhoneVerification(Request $request)
    {
        $user = $request->user();
        $user->otp = rand(77777, 99999);
        $user->save();
        $request->user()->notify(new SmsNotifications("Your phone verification code is $user->otp"));
        return $this->respondWithSuccess('Voice OTP has been sent');
    }

    public function resendMailVerification(Request $request)
    {
        $request->user()->notify(new EmailVerificationRequest());
        return $this->respondWithSuccess('E-Mail verification mail has been sent');
    }

    public function verifyPhone(Request $request)
    {
        $request->validate(['otp' => ['required', 'min:5']]);
        $user = $request->user();
        if ($user->otp !== $request->otp) {
            return $this->respondWithError('In valid OTP');
        }
        $user->is_verified = true;
        $user->otp = null;
        $user->save();
        return $this->respondWithSuccess(['message' => 'Email verified successfully', 'data' => $this->prepareUserData($user)]);
    }

    public function verifyMail(Request $request)
    {
        $request->validate(['otp' => ['required', 'min:6']]);
        $user = $request->user();
        $token = UserVerification::whereUserId($user->id)->whereToken($request->otp)->first();
        if (!$token) {
            return $this->respondWithError('Invalid OTP');
        }
        $user->email_verified_at = now();
        $user->save();
        $token->delete();

        return $this->respondWithSuccess(['message' => 'Email verified successfully', 'data' => $this->prepareUserData($user)]);
    }


    public function update(Request $request)
    {
        $user = getUser();
        $validations = [
            'email' => 'nullable|email',
            'user_type' => 'nullable|in:creatives,audience,user,admin',
            'phone' => 'nullable|min:11',
            'avatar' => ['sometimes', 'file', 'mimes:jpeg,jpg,bmp,png|max:2048'],
        ];
        if ($request->email && $user->email !== $request->email) $validations['email'] = 'unique:users,email|required|email';
        if ($request->phone && $user->phone !== $request->phone) $validations['phone'] = 'unique:users,phone|required|min:11';
        $request->validate($validations);
        $user->update($request->except(['phone', 'email']));
        if ($file = $request->file('avatar')) $user->updateMedia($request->avatar);
        return $this->respondWithSuccess($this->prepareUserData($user));
    }


    public function cards()
    {
        return $this->respondWithSuccess(['data' => getUser()->cards()], 201);
    }

    public function deleteCard($id)
    {
        $card = getUser()->cards()->findOrFail($id);
        $card->delete();
        return $this->respondWithSuccess('Deleted successfully');
    }

    public function deleteUser($id)
    {
        $user = User::whereEmail($id)->firstOrFail();
        $user->events()->get()->each->delete();
        $user->tips()->get()->each->delete();
        $user->wishes()->get()->each->delete();
        $user->delete();
        return $this->respondWithSuccess('Deleted successfully');
    }


    public function getTransactions(Request $request)
    {
        $user = $request->user('api');
        $data['withdrawn'] = $user->withdrawal;
        $data['given'] = $user->given;
        $data['received'] = $user->tip_received;
        return $this->respondWithSuccess($data);
    }


    public function socialLogin(Request $request): JsonResponse
    {
        $request->validate(['provider_name' => 'required', 'access_token' => 'required']);

        $provider = $request->provider_name;
        $token = $request->access_token;

        $providerUser = Socialite::driver($provider)->userFromToken($token);
        $user = User::updateOrCreate(['email' => $providerUser->email], [
            'first_name' => $providerUser->name, 'last_name' => " ",
            'email' => $providerUser->email,
            'provider_id' => $providerUser->id,
            'provider_name', $provider,
            'avatar' => $providerUser->getAvatar()
        ]);
        $token = $user->createToken($this->api_key)->accessToken;
        return $this->respondWithSuccess(['data' => ['token' => $token, 'user' => $this->prepareUserData($user)]], 201);
    }
}
