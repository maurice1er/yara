<?php

namespace App\Http\Controllers\Auths;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationController extends BaseController
{
    public function __construct() {
        $this->middleware('auth:api')->except(['verify']);
    }

    
    public function verify($user_id, Request $request) {
        
        if (!$request->hasValidSignature()) {
            return $this->sendError('Invalid email address verification', 'Invalid email verification.');
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return $this->sendResponse('Email address verified.', 'Email address verified.');    
    }


    public function resend() {
        if (auth()->user()->hasVerifiedEmail()) {
            return $this->sendError('Email already verified.', 'Email already verified');
        }

        auth()->user()->sendEmailVerificationNotification();

        return $this->sendResponse('Verification email sending', 'Verification email sending');
    }
}
