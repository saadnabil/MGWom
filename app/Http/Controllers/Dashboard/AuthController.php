<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\LoginRequestValidation;
use App\Http\Resources\Api\UserResource;
use App\Http\Resources\Dashboard\AdminResource;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //
    use ApiResponseTrait;
    public function login(LoginRequestValidation $request){
        $data = $request->validated();
        $token = auth()->guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']]);
        if (!$token) {
            return $this->sendResponse(['error' => __('dashboard.Invalid Credintials')] , 'fail' , 404);
        }
        $admin = Auth::guard('admin')->user();
        $admin['token'] = $token;
        /**Eager loading for roles and permissions*/
        $admin = $admin->load('roles.permissions');
        return $this->sendResponse(new AdminResource($admin));
    }

    public function logout(){
        auth()->guard('admin')->logout();
        return $this->sendResponse([]);
    }
}
