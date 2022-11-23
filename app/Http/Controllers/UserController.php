<?php

namespace App\Http\Controllers;
use App\Repositories\UserRepository;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @return array
     */

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

    final public function getAuthenticatedUser(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $authenticated = getUser()->id;
        if ($user->id == $authenticated) return $this->respondWithSuccess(['data'=>['message' =>'Authenticated User', 'user' => $this->repo->prepareUserData($user)]], 201);
        return $this->respondWithError(['data'=>['message' =>'Account not found', 'user' => []]], 404);
    }

    public function getUser($id)
    {
        $user = User::findOrFail($id);
        return $this->respondWithSuccess(['data' => ['user' => $this->prepareUserData($user)]], 201);
    }

    // public function deleteUser($id)
    // {
    //    $user = User::whereEmail($id)->firstOrFail();
    //    $user->events()->get()->each->delete();
    //    $user->tips()->get()->each->delete();
    //    $user->wishes()->get()->each->delete();
    //    $user->delete();
    //     $user = User::find($id);
    //     $user -> delete();

    //    return $this->respondWithSuccess(['data' => ['message' => 'Deleted successfully', 'user' => $this->repo->prepareUserData($user)]], 201);
    // }

    final public function deleteUser($id)
    {
        // if (auth()->user()->user_type = 'admin'){
            $user = User::destroy($id);
            return $this->respondWithSuccess(['data' => ['message' => 'user '.$id.' has been deleted' ,'pricing' => $user]], 201);
        // } else {
            // return$this->respondWithSuccess(['message' => 'Only Admin can delete User'], 201);
        // }
    }

    public function updateUser(Request $request, $id){
        $request->validate([
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
             'phone_number' => 'required|unique:users,phone_number',
            
        ]);
     
        try{
            $user = User::findorfail($id)->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'phone_number'=>$request->phone_number,
                'avatar'=>$request->avatar,
                'location'=>$request->location,
                'bio'=>$request->bio,
                'tags'=>$request->tags,
                'banner'=>$request->banner,
                'password'=>Hash::make($request->password),
            ]);
            // generate Random Token min of 10 and  characters
             $token = rand(10,100000);
            return response([
                'message'=>'user updated successful',
                'token'=>$token,
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
        return $this->respondWithSuccess(['data' => ['message' => 'All talencroft users', 'users' => $users]], 201);
    }
}
