<?php

use App\Http\Controllers\Auths\AuthController;
use App\Http\Controllers\Auths\EmailVerificationController;
use App\Http\Controllers\Auths\ForgotPasswordController;
use App\Http\Controllers\Components\AlgorithmController;
use App\Http\Controllers\Components\DataController;
use App\Http\Controllers\Components\WorkspaceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RuleController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

// Route::apiResource('auth', AuthController::class);


// Route : Rules & Permissions
Route::apiResource('rule', RuleController::class);
Route::apiResource('permission', PermissionController::class);

// Route : Components
Route::apiResource('workspace', WorkspaceController::class);
Route::apiResource('data', DataController::class);
Route::apiResource('algorithm', AlgorithmController::class);


// Route::post('register', 'RegistrationController@register');
Route::get('email/verify/{id}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');

Route::post('password/email', [ForgotPasswordController::class, 'forgot'])->name('password.forgot');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.reset');