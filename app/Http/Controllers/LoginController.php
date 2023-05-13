<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Traits\ShowDataTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ShowDataTrait;

    public function login(LoginRequest $request){
        if (Auth::attempt($request->only(['email' , 'password']))) {
            $token = $request->user()->createToken('api_token')->plainTextToken;
            return $this->succes('Logged in successfully', $token) ;
        }
        return $this->customError(JsonResponse::HTTP_UNAUTHORIZED , 'Invalid login credentials');
    }
}
