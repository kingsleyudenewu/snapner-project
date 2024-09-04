<?php

namespace App\Http\Controllers;

use App\Actions\LoginAction;
use App\Data\RegisterData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $userData = RegisterData::from($request);

        User::create($userData->toArray());

        return $this->createdResponse('User registered successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request, LoginAction $action): \Illuminate\Http\JsonResponse
    {
        if(!Auth::attempt($request->only(['email', 'password']))){
            return $this->badRequestAlert('Invalid login credentials');
        }

        $generateToken = $action->execute($request->toArray());

        return $this->successResponse('User Logged In Successfully', $generateToken);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {
        request()->user()->tokens()->delete();

        return $this->successResponse('User Logged Out Successfully');
    }
}
