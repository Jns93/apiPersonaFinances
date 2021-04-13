<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\StoreUser;
use App\Http\Resources\UserResource;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function registerUser(StoreUser $request)
    {
        $user = $this->userService->registerUser($request->all());

        return new UserResource($user);
    }
}
