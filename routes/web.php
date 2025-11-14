<?php

use App\Http\Controllers\Lti\JwksController;
use App\Http\Controllers\Lti\LtiDeepLinkController;
use App\Http\Controllers\Lti\LtiErrorController;
use App\Http\Controllers\Lti\LtiLaunchController;
use App\Http\Controllers\Lti\LtiLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// LTI login, launch, and services
Route::post('/lti/login', [LtiLoginController::class, 'login'])->name('lti.login');
Route::post('/lti/launch', [LtiLaunchController::class, 'launch'])->name('lti.launch');
Route::get('/lti/deep-link', [LtiDeepLinkController::class, 'index'])->name('lti.deep_link');
Route::post('/lti/deep-link/response', action: [LtiDeepLinkController::class, 'response'])->name('lti.deep_link.response');
Route::get('/lti/error', [LtiErrorController::class, 'index'])->name('lti.error');

// JWKS endpoint
Route::get('/.well-known/jwks.json', [JwksController::class, 'keys'])->name('lti.keys');
