<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function index()
    {

    }


    public function login(Request $request)
    {
        $this->getValidate($request);
        if (!Auth::attempt($request->only('email', 'password'))) {

            return response()->json([
                'message' => 'invalid_credential',
                'success' => false
            ], 401);
        }
        $user = User::where('email', $request['email'])->firstOrFail();
        $user->tokens()->delete();
        $access_token = $user->createToken('trexQr')->plainTextToken;

        return response([
            'access_token' => $access_token,
            'token_type' => 'Bearer',
            'success' => true,
            'user' => $user
        ], 201);

    }

    /**
     * @param Request $request
     * @return void
     */
    public function getValidate(Request $request): void
    {
        $is_login = Str::contains(Route::currentRouteAction(), '@login');
        $validate_items = [
            'name' => $is_login ? '' : 'required|unique:users,name',
            'email' => $is_login ? 'email|required|min:3' : 'email|required|min:3|unique:users,email',
            'password' => 'required'];
        $request->validate($validate_items);
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response([
            'access_token' => null,
            'message' => 'loged_out',
            'success' => true,
        ], 201);
    }


}
