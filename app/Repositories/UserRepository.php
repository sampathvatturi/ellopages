<?php
namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UserRepository
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function register($postData)
    {
       try{
            $user = $this->user->create([
                'u_name' => $postData->u_fname.' '.$postData->u_lname,
                'u_email' => $postData->u_email,
                'u_password' => $postData->u_password,
                'u_phone' => $postData->u_phone ?? '',
                'u_reference_id' => $postData->u_reference_id ?? 0,
            ]);

            if($user)
                return true;
            else
                return false;
       }catch(Exception $e){
        dd($e->getMessage());
            Log::error($e->getMessage());
            return false;
       }
    }

    public function getUserByEmail($email){
        return $this->user->where('u_email', $email)->first();
    }

    public function updateDataByEmail($email, $newData){
        $user = $this->getUserByEmail($email);
        if($user){
            $user->update($newData);
            return true;
        } else{
            return false;
        }
    }
}