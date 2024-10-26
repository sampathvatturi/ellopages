<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use App\Services\FirebaseAuthService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    protected $authService;
    protected $firebaseAuthService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->firebaseAuthService = new FirebaseAuthService();
    }
    // Registration method
    public function register(Request $request)
    {
        try {
            $request->validate([
                'u_fname' => 'required|string|max:255',
                'u_lname' => 'required|string|max:255',
                'u_email' => 'required|string|email|max:255|unique:tbl_users,u_email',
                'u_phone' => 'required|min:10|unique:tbl_users,u_phone',
                'u_password' => 'required|string|min:6|confirmed',
                
            ]);
            
            if($request){
                $status = $this->authService->register($request);
                if($status !== 'alreadyExists'){
                    return response()->json(['message' => 'User registered successfully'], 201);
                }else{
                    return response()->json(['message'=> 'User already exists'],409);
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    // Login method
    public function login(Request $request)
    {
        // Validate the login request
        try {
            $request->validate([
                'u_email' => 'required|string|email',
                'u_password' => 'required|string',
            ]);
            
            // Attempt to authenticate user
            if($request){
                $token = $this->authService->login($request);
                if($token){
                    return response()->json(['token' => $token], 200);
                }else{
                    return response()->json(['message' => 'Invalid credentials'], 401);
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } 
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $request->validate([
                'uid' => 'required|string'
            ]);

            $uid = $request->input('uid');
    
            // $verificationResult = $this->firebaseAuthService->verifyIdToken($request->idToken);
    
            // if ($verificationResult['success']) {
                // Now that the token is verified, get user data
                $status = $this->firebaseAuthService->getUserData($uid);
                if($status !== 'alreadyExists'){
                    return response()->json(['message' => 'User registered successfully'], 201);
                }else{
                    return response()->json(['message'=> 'User already exists'],409);
                }
                // return response()->json($userData);
            // }
    
            // return response()->json($verificationResult);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error_type' => 'general_error'
            ], 401);
        }
    }

}
