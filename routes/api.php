<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
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

Route::get('not', [UserController::class, 'notAuth'])->name('not');
Route::prefix('v1')->group(function () {
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::post('login', 'login');
        Route::post('create', 'createUser');
        Route::post('logout', 'logout');
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('getuser', 'getUser');
            Route::get('getuserjobs', 'getUserListing');
            Route::post('updateprofile', 'updateProfile');
            Route::post('confirm', 'confirmEmail');
            Route::post('verify', 'verifyCode');
            Route::post('add-education', 'updateEducation');
            Route::post('add-experience', 'updateExperience');
        });
    });
    Route::controller(ListingController::class)->prefix('jobs')->group(function () {
        Route::get('getjobs', 'getJobs')->middleware('auth:sanctum');
        Route::get('getsinglejob/{id}', 'getJobById');
        Route::post('create', 'postJob')->middleware('auth:sanctum', 'role:employer');
    });
});
Route::get('getjobs', [ListingController::class, 'getjobs']);
Route::get('getonejob/{id}', [ListingController::class, 'getonejob']);
Route::middleware('auth:api', 'verified')->prefix('v1')->group(function () {
    Route::post('postjob', [ListingController::class, 'postjob']);
});
Route::middleware('auth:api', 'isadmin', 'verified')->prefix('admin')->group(function () {
    Route::post('approvejob/{id}', [AdminController::class, 'approvejob']);
});


Route::post('forgotpassword', [UserController::class, 'ResetPassword']);
Route::post('verifyresetcode', [UserController::class, 'VerifyResetCode']);
Route::post('changepassword', [UserController::class, 'ChangePassword'])->middleware('auth:api');
Route::post('verifycode', [UserController::class, 'verifyEmail'])->middleware('auth:api');
Route::post('resendcode', [UserController::class, 'Resendcode'])->middleware('auth:api');
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:api');
Route::post('updateprofile', [UserController::class, 'updateProfile'])->middleware('auth:api');
Route::delete('/deletejob/{id}', [ListingController::class, 'Deletejob'])->middleware('auth:api');
Route::put('deletejob/{id}/undo', [ListingController::class, 'Undodelete'])->middleware('auth:api');
