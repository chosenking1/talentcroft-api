<?php
header('Accept: application/json', true);

use App\Http\Controllers\{
    UserController,
    ProjectController,
    SettingsController,
    ProjectFileController,
    ProjectTicketController,
    ProjectDiscountController,
    ProjectDiscountUsagesController,
    ApiController
};
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//public routes
    /** AUTHENTICATION AND USER DETAILS */
    Route::group(['prefix' => 'user'], function () {
        Route::post('/login', [UserController::class, 'login']);
        Route::post('/register', [UserController::class, 'register']);
<<<
    });



