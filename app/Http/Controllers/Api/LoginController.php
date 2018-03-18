<?php

namespace App\Http\Controllers\Api;

use App\Domain\User\Requests\LoginRequest;
use App\Domain\User\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function authenticate(LoginRequest $request)
    {
        if (! auth()->attempt($request->only('email', 'password'))) {
            throw new AuthenticationException(trans('auth.failed'));
        }

        return (new UserResource(auth()->user()))->additional([
            'meta' => [
                'token' => 'Bearer ' . auth()->user()->createToken(
                    config('app.key')
                )->accessToken,
            ],
        ]);
    }
}
