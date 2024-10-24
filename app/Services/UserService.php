<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAuthUserData($postData)
    {
        // Access the authenticated user
        $user = $postData->user();

        if($user)
            return $user;
        else
            return null;
    }
    
}