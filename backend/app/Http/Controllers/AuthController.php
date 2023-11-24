<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public  function login(Request $request) {
        $credentials = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($credentials->fails()) {
            return $this->failed(['message' => 'Validation Error', 'errors' => $credentials->errors()]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->success(['message' => 'Login Success', 'user' => $user, 'token' => $token]);
        }
        return $this->failed(['message' => 'Username and password inccorect']);
    }

    public function register(Request $request) {
        $credentials = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($credentials->fails()) {
            return $this->failed(['message' => 'Validation Error', 'errors' => $credentials->errors()]);
        }

        $request['password'] = bcrypt($request->password);
        $create = User::create($request->all());

        if ($create) {
            $token = $create->createToken('auth_token')->plainTextToken;
            return $this->success(['message' => 'Register Success', 'user' => $create, 'token' => $token]);
        }
        return $this->failed(['message' => 'Register Failed']);
    }
}
