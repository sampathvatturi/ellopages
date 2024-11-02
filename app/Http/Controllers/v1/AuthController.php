<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\v1\AuthService;
use App\Services\v1\FirebaseAuthService;
use App\Services\v1\UserService;
use Illuminate\Http\Request;

class AuthController extends CommonController
{
    protected $authService;
    protected $firebaseAuthService, $userService;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->firebaseAuthService = new FirebaseAuthService();
        $this->userService = new UserService();
    }
    // Registration method
    public function register(Request $request)
    {
        try {
            $request->validate([
                'u_fname' => 'required|string|max:255',
                'u_lname' => 'required|string|max:255',
                'u_email' => 'required|string|email|max:255|unique:tbl_users,u_email',
                'u_phone' => 'required|integer|min:10|unique:tbl_users,u_phone',
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

    public function registerApple(Request $request)
    {
        try {
            $request->validate([
                'u_fname' => 'required|string|max:255',
                'u_lname' => 'required|string|max:255',
                'u_email' => 'required|string|email|max:255|unique:tbl_users,u_email',
                'u_phone' => 'integer|min:10|unique:tbl_users,u_phone',
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

    public function validateEmail(Request $request){
        try {
            $request->validate([
                'u_email' => 'required|string|email',
            ]);
            $isEmailExists = $this->userService->checkEmailExists($request->u_email);
            if($isEmailExists){
                $sendOtp = $this->authService->sendMailOtp($request->u_email);
                return response()->json(['status' => true,'message' => 'OTP sent.'], 200);
            }else{
                return response()->json(['status' => false, 'message' => 'Email not exists.'], 404);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    public function validateOtp(Request $request){
        try {
            $request->validate([
                'u_email' => 'required|string|email|max:255|exists:tbl_users,u_email',
                'u_otp' => 'required|integer|digits:4',
            ]);
            $validateOtp = $this->authService->validateOtp($request->u_email,$request->u_otp);
            if($validateOtp){
                return response()->json(['status' => true,'message' => 'OTP is valid.'], 200);
            }else{
                return response()->json(['status' => false, 'message' => 'OTP is invalid.'], 422);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    public function resetPassword(Request $request){
        try {
            $request->validate([
                'u_email' => 'required|string|email|max:255|exists:tbl_users,u_email',
                'u_otp' => 'required|integer|digits:4',
                'u_password' => 'required|string|min:6|confirmed',
            ]);
            $resetPassword = $this->authService->resetPassword($request);
            if($resetPassword){
                return response()->json(['status' => true,'message' => 'Password reset successfully.'], 200);
            }else{
                return response()->json(['status' => false, 'message' => 'Failed to reset password.'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e){
            return response()->json([
                'error_type' => 'general_error',
               'message' => $e->getMessage()
            ], 500);
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
