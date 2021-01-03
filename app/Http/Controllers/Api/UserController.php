<?php

namespace App\Http\Controllers\Api;

use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $credentials = $request->only('email', 'password');

                try {
                    if (!$token = JWTAuth::attempt($credentials)) {
                        return response()->json(['error' => 'invalid_credentials'], 400);
                    }
                } catch (JWTException $e) {
                    return response()->json(['error' => 'could_not_create_token'], 500);
                }

                $token = compact('token');

                return response()->json($token)->withCookie('token', $token['token'], 60, null, null, false, false);
            }

            return $this->error('Pasword Salah!');
        }

        return $this->error('Email tidak ditemukan!');
    }
    public function register(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);
        if ($validasi->fails()) {
            $val = $validasi->errors()->all();

            return $this->error($val);
        }

        // Create data
        $user = User::create(array_merge($request->all(), [
            'password' => bcrypt($request->password)
        ]));

        if ($user) {
            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user', 'token'), 201);
        }

        return $this->error('Registrasi gagal!');
    }
    public function error($message)
    {
        return response()->json([
            'success' => 0,
            'message' => $message
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // Tell Laravel to forget this cookie
        $cookie = \Cookie::forget('token');
        return response()->json([
            'message' => 'Has been logout!'
        ])->withCookie($cookie);
    }

    public function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }
}