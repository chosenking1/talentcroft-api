<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    final public function versionOne(): JsonResponse
    {
        return response()->json(['status' => 'success', 'message' => 'GET GRID API Version 1', 'data' => [
            'v1' => url('api/v1/'),
            'documentation' => 'https://documenter.getpostman.com/view/1801697/2s83ziQQKW'
        ]]);
    }
}
