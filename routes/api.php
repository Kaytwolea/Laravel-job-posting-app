<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('getjobs', [ListingController::class, 'getjobs']);
Route::get('getonejob/{id}', [ListingController::class, 'getonejob']);
Route::middleware('auth:api', 'verified')->prefix('v1')->group(function () {
    Route::post('postjob', [ListingController::class, 'postjob']);
    Route::get('getuser', [UserController::class, 'getUser']);
    Route::get('getuserjobs', [UserController::class, 'getUserListing']);
});
Route::post('createuser', [UserController::class, 'createUser']);
Route::post('login', [UserController::class, 'login']);
Route::post('forgotpassword', [UserController::class, 'ResetPassword']);
Route::post('verifyresetcode', [UserController::class, 'VerifyResetCode']);
Route::post('changepassword', [UserController::class, 'ChangePassword'])->middleware('auth:api');
Route::post('verifycode', [UserController::class, 'verifyEmail'])->middleware('auth:api');
Route::post('resendcode', [UserController::class, 'Resendcode'])->middleware('auth:api');
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:api');
Route::post('updateprofile', [UserController::class, 'updateProfile'])->middleware('auth:api');
Route::delete('/deletejob/{id}', [ListingController::class, 'Deletejob'])->middleware('auth:api');
Route::put('deletejob/{id}/undo', [ListingController::class, 'Undodelete'])->middleware('auth:api');
