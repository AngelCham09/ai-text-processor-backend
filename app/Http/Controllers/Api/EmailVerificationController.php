<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return ApiResponse::error('Invalid verification link.', null, 403);
        }

        if ($user->hasVerifiedEmail()) {
            return ApiResponse::success('Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return ApiResponse::success('Email successfully verified.');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return ApiResponse::error('Email already verified.', null, 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return ApiResponse::success('Verification link sent to your email.');
    }
}
