<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Http\Requests\SettingsRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private $paystack, $userRepo;
    

    public function __construct(  UserRepository $repo)
    {
        $this->userRepo = $repo;
    }

    // public function defaultSettings(Request $request)
    // {
    //     $user = $request->user()->setting();
    //     return $this->respondWithSuccess($user);
    // }


    public function updateSettings(Request $request)
    {
        $user = getUser();
        $user->setting()->firstOrCreate([],[])->update($request->all());
        return $this->respondWithSuccess($this->userRepo->prepareUserData($user), 201);
    }

}
