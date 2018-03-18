<?php

namespace App\Http\Controllers\Api;

use App\Domain\User\Entities\User;
use App\Domain\User\Requests\RegisterRequest;
use App\Domain\User\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        return (new UserResource($user))->additional([
            'meta' => [
                'token' => 'Bearer ' . $user->createToken(
                    config('app.key')
                )->accessToken,
            ],
        ]);
    }

    protected function create(array $data) : User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
