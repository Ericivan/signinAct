<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13
 * Time: 10:48
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();

            $token = $user->createToken('MyApp')->accessToken;

            return response()->json([
                'token' => $token,
                'msg' => 'ok',
            ]);
        }
    }

    public function register()
    {

    }
}