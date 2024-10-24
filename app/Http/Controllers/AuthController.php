<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }
    // Registration method
    public function register(Request $request)
    {
        // Validate the request
        // $request->validate([
        //     'u_fname' => 'required|string|max:255',
        //     'u_lname' => 'required|string|max:255',
        //     'u_email' => 'required|string|email|max:255|unique:tbl_users,u_email',
        //     'u_password' => 'required|string|min:6|confirmed',
        // ]);
        
        if($request){
            $status = $this->authService->register($request);
// dd($status);
            if($status !== 'alreadyExists'){
                return response()->json(['message' => 'User registered successfully'], 201);
            }else{
                return response()->json(['message'=> 'User already exists'],409);
            }
        }
    }

    // Login method
    public function login(Request $request)
    {
        // Validate the login request
        // $request->validate([
        //     'u_email' => 'required|string|email',
        //     'u_password' => 'required|string',
        // ]);

        // Attempt to authenticate user
        if($request){
            $token = $this->authService->login($request);
            if($token){
                return response()->json(['token' => $token], 200);
            }else{
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        }
    }
}
