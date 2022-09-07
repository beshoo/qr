<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Services\Ip2location;

class RegistrationController extends Controller
{
    public function index()
    {
    }

    public function store(RegistrationRequest $request)
    {
        $attributes = array_merge($request->getAttributes(), ['country' => Ip2location::lookup($_SERVER['REMOTE_ADDR'])]);
        $attributes['password'] = bcrypt($attributes['password']);
        $user = User::create($attributes);
        $user->sendEmailVerificationNotification();

        return response([
            //            'user' => $user,
            'success' => true,
            'message' => 'Thank you for subscribing, we have sent you a verification email, please check your inbox, and your spam folder!',
        ], 201);
    }
}
