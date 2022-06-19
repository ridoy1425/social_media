<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
    	$user = User::where('email',$request['email'])->first();

        if($user)
        {
            $response['status'] = 0;
            $response['message'] = 'email is already taken'; 
            return response()->json($response);
        }
        else
        {
            $users =  User::create([
                'fname' => $request['fname'],
                'lname' => $request['lname'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
            return response()->json($request);
        }        

        
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            $response['token'] = $token;
            $response['users'] = Auth::user();

            return response()->json($response);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }  

    
    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => $this->guard()->factory()->getTTL() * 60
    //     ]);
    // }

    public function guard()
    {
        return Auth::guard();
    }
}
