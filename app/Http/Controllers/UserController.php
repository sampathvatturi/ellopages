<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function getUserData(Request $request){
        $user = $this->userService->getAuthUserData($request);

        return response()->json($user);
    }
}
