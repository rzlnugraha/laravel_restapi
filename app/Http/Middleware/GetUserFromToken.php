<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class GetUserFromToken extends BaseMiddleware
{
    public function handle($request, \Closure $next)
    {
        $code = 401;
        $status = array(
            'success' => false, 
            'code' => $code, 
            'message' => 'token not found'
        );
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            $response = [
                'code' => $code,
                'message' => 'Authorization Not Provided'
            ];
            return response()->json($response, $code);
        }
        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            $message = 'Authorization Expired';
            $code;
            $response = [
                'code' => $code,
                'message' => $message
            ];
            return response()->json($response, $code);
        } catch (JWTException $e) {
            $message = 'Authorization Invalid';
            $response = [
                'code' => $code,
                'message' => $message
            ];
            return response()->json($response, $code);
        }
        if (! $user) {
            $message = 'User not found';
            $response = [
                'code' => $code,
                'message' => $message
            ];
            return response()->json($response, $code);
        }
        return $next($request);
    }
}
