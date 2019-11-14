<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected function respondWithToken($token)
    {
        $code = 200;
        return response()->json([
                'code' => $code,
                'message' => 'success',
                'content' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60
                ]
        ],$code);
    }
    
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name, 
            'email' => $request->email, 
            'password' => $request->password
        ]);

        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    public function login(LoginRequest $request)
    {
        $credentials = request([
            'email',
            'password'
        ]);
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                $code = 404;
                $response = [
                    'code' => $code,
                    'message' => 'Email yang anda masukan salah'
                ];
                return response()->json($response, $code);
            }
        } catch (JWTException $e) {
            $response = [ 'status' => $e ];
            return response()->json($response, 404);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $logout = JWTAuth::invalidate();
        $code = 200;
        $response = [
            'code' => $code,
            'message' => 'Berhasil logout',
            'content' => $logout
        ];

        return response()->json($response, $code);
    }

    public function refresh()
    {
        $code = 200;
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);
        $response = [
            'code' => $code,
            'message' => 'New Token',
            'content' => $newToken
        ];
        return response()->json($response, $code);
    }

    public function updateName(UpdateProfileRequest $request)
    {
        $cek = JWTAuth::parseToken()->authenticate();
        $user_id = $cek->id;
        $name = $request->name;
        $email = $request->email;

        $user = User::find($user_id);
        $user->name = $name;
        $user->email = $email;
        $user->save();

        $code = 200;
        $response = [
            'code' => $code,
            'message' => 'Berhasil rubah data',
            'content' => $user
        ];
        return response()->json($response, $code);
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $cek = JWTAuth::parseToken()->authenticate();
        $credentials = [
            'email' => $cek->email,
            'password' => $request->old_password
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            $code = 404;
            $response = [
                'code' => $code,
                'message' => 'Password lama salah'
            ];
            return response()->json($response, $code);
        }
        // dd(1);
        $user_id = $cek->id;
        $password = $request->password;

        $user = User::find($user_id);
        $user->password = $password;
        $user->save();

        $code = 200;
        $response = [
            'code' => $code,
            'message' => 'Berhasil rubah data',
            'content' => $user
        ];
        return response()->json($response, $code);
    }
}
    