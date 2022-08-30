<?php

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::middleware(['auth:sanctum', 'verified'])->group(function ()
{
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('signup', [\App\Http\Controllers\RegistrationController::class, 'store']);
Route::get('email/verification/{id}', [\App\Http\Controllers\VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/verification/resend', [\App\Http\Controllers\VerificationController::class, 'resend'])->name('verification.resend');
Route::post('email/forget_password/send', [\App\Http\Controllers\ForgotPasswordController::class, 'forgot']);
Route::post('email/forget_password/reset', [\App\Http\Controllers\ForgotPasswordController::class, 'reset']);

