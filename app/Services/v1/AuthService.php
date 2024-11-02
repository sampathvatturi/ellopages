<?php

namespace App\Services\v1;

use App\Mail\MailOtp;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function sendMailOtp($email){
        $otp = mt_rand(1000, 9999);
        $updateArray = [
            'u_otp' => $otp,
            'u_otp_timestamp' => date('Y-m-d H:i:s') // Set OTP expiry after 10 minutes
        ];
        $this->userRepository->updateDataByEmail($email,$updateArray);
        //use laravel mail service to senmd mail
        $status = Mail::to($email)->send(new MailOtp($otp));
        return $status;
    }

    public function validateOtp($email,$otp){
        $user = $this->userRepository->getUserByEmail($email);
        if ($user) {
            if($user->u_otp == $otp){
                return true;
            }
        }
        return false;
    }

    public function resetPassword($postData){
        $user = $this->userRepository->getUserByEmail($postData->u_email);
        if ($user) {
            if($user->u_otp == $postData->u_otp){
                $updateArray = [
                    'u_password' => $postData->u_password,
                    'u_otp' => null,
                    'u_otp_timestamp' => null
                ];
                return $this->userRepository->updateDataByEmail($postData->u_email,$updateArray);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}