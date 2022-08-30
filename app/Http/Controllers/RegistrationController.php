<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;


class RegistrationController extends Controller
{
    public function index()
    {

    }

    public function store(RegistrationRequest $request)
    {
        $user = User::create($request->getAttributes());
        $user->sendEmailVerificationNotification();

        return response([
            'token' => $user->createToken('trexQr')->plainTextToken,
            'user' => $user,
        ], 201);
    }
}
