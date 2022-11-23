<?php

namespace App\Http\Controllers;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;


class UserController extends Controller
{

    private $repo;
    private string $api_key = 'VIEWS_API';

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

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
            // $data['avatar'] = textToImage(text: 'No avatar', bg: randomColorCode());
            $user = User::create($data);
            return $user;
        });
        $accessToken = $user->createToken($this->api_key)->accessToken;
        return $this->respondWithSuccess(['data' => ['token' => $accessToken, 'user' => $this->repo->prepareUserData($user)]], 201);
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

    public function updateUser(Request $request, $id){
        $request->validate([
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'required|email|unique:users',
             'phone_number' => 'required|unique:users,phone_number',
            
        ]);
     
        try{
            $user = User::findorfail($id)->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'phone_number'=>$request->phone_number,
                // 'password'=>Hash::make($request->password),
            ]);
            // $token = $user->createToken('app')->accessToken;

            return response([
                'message'=>'user updated successful',
                // 'token'=>$token,
                'user'=>$user,
            ], 200);
        }catch(Exception $exception){
            return response([
                'message'=>$exception->getMessage(),
            ], 400);
        }


        
    }

    public function getAllUsers(){
        $users = User::latest()->get();
        return response()->json($users);
    }
}
