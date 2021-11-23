<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
           'name'=>'required|string',
           'email'=>'required|string|email|unique:users',
           'password'=> 'required|string|confirmed'
        ]);

        $user = User::create([
           'name'=>$request->get('name'),
           'email'=>$request->get('email'),
           'password'=>bcrypt($request->get('password'))
        ]);

        return response([
           "message" => "user created",
            "user" => $user
        ]);
    }

    public function login(Request $request){
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];


        if (Auth::attempt($credentials)) {


            $user = Auth::user();

            $token = $user->createToken('myTokken')->plainTextToken;


            return response([
                'user' => $user,
                'token' => $token
            ]);
        }else{
            return "invalid";
        }
    }

    public function logout() {
        Auth::user()->tokens()->delete();
        return response([
           "message" => "You are logged out"
        ]);
    }
}
