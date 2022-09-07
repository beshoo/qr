<?php

use Illuminate\Support\Facades\Route;

ini_set('memory_limit', '512M');
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/*
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('qr_list', [\App\Http\Controllers\QrsController::class, 'index']);
    Route::post('qr_store', [\App\Http\Controllers\QrsController::class, 'store']);
});*/

Route::prefix('')->group(function () {
    Route::post('register', [\App\Http\Controllers\RegistrationController::class, 'store']);
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('token_refresh', 'App\Http\Controllers\AuthController@token_refresh');
    Route::get('qr_show/{qr_id}', [\App\Http\Controllers\QrController::class, 'show']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('me', [\App\Http\Controllers\AuthController::class, 'me']);
        Route::get('qr_list', [\App\Http\Controllers\QrController::class, 'index']);
        Route::post('qr_detect', [\App\Http\Controllers\QrController::class, 'detect']);
        Route::get('qr_delete', [\App\Http\Controllers\QrController::class, 'destroy']);
        Route::post('qr_store', [\App\Http\Controllers\QrController::class, 'store']);
        Route::post('qr_update', [\App\Http\Controllers\QrController::class, 'update']);
        Route::get('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});

/*Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

});*/

//Route::post('signup', [\App\Http\Controllers\RegistrationController::class, 'store']);
Route::get('email/verification/{id}', [\App\Http\Controllers\VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/verification/resend', [\App\Http\Controllers\VerificationController::class, 'resend'])->name('verification.resend');
Route::post('email/forget_password/send', [\App\Http\Controllers\ForgotPasswordController::class, 'forgot']);
Route::post('email/forget_password/reset', [\App\Http\Controllers\ForgotPasswordController::class, 'reset']);
