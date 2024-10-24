<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    public function register($postData)
    {
        if (isset($postData)){
            $user = $this->userRepository->getUserByEmail($postData->u_email);
            if($user){
                return 'alreadyExists';
            }
            return $this->userRepository->register($postData);
        }
    }

    // Login method
    public function login($postData)
    {
        if (isset($postData)){
            $user = $this->userRepository->getUserByEmail($postData->u_email);

            if (!$user || !Hash::check($postData->u_password, $user->u_encrypted_password)) {
                return false;
            }

             // Create a token for the user
            $token = $user->createToken('mobile-app-token')->plainTextToken;

            return $token;

        }
    }
}