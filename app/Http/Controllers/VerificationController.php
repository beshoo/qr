<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['verify']);
    }

    public function index()
    {
    }

    /**
     * Verify email
     *
     * @param $user_id
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function verify($user_id, Request $request)
    {
        if (! $request->hasValidSignature()) {
            return response([
                'success' => false,
                'message' => 'respondUnAuthorizedRequest',
            ]);
        }

        $user = User::findOrFail($user_id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/');
    }

    /**
     * Resend email verification link
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return $this->respondBadRequest(ApiCode::EMAIL_ALREADY_VERIFIED);
        }
        auth()->user()->sendEmailVerificationNotification();

        return $this->respondWithMessage('Email verification link sent on your email id');
    }
}
