<?php

use App\Http\Controllers\AuthSocialite\GithubController;
use App\Http\Controllers\AuthSocialite\GoogleController;
use App\Http\Controllers\AuthSocialite\LinkedinController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/callback', [GoogleController::class, 'hangleGoogleCallback']);
Route::get('/auth/google/redirect', [GoogleController::class, 'hangleGoogleRedirect']);


Route::get('/auth/github/callback', [GithubController::class, 'hangleGithubCallback']);
Route::get('/auth/github/redirect', [GithubController::class, 'hangleGithubRedirect']);


Route::get('/auth/linkedin/callback', [LinkedinController::class, 'hangleLinkedinCallback']);
Route::get('/auth/linkedin/redirect', [LinkedinController::class, 'hangleLinkedinRedirect']);
