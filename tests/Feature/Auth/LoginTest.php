<?php

namespace Tests\Feature\Auth;

use App\Domain\User\Entities\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testLoginSuccessfully()
    {
        $user = factory(User::class)->create();

        $payload = [
            'email' => $user->email,
            'password' => 'secret',
        ];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'avatar',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'meta' => [
                    'token'
                ],
            ]);
    }

    public function testLoginFailed()
    {
        $faker = Factory::create();

        $payload = [
            'email' => $faker->safeEmail,
            'password' => str_random(6),
        ];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(401)
            ->assertJson([
                'message' => 'These credentials do not match our records.',
            ]);
    }

    public function testRequiredEmailAndPassword()
    {
        $this->json('POST', '/api/login')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
            ]);
    }

    public function testWrongEmailFormat()
    {
        $payload = [
            'email' => str_random(10),
            'password' => '123456',
        ];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['The email must be a valid email address.'],
                ],
            ]);
    }

    public function testPasswordLessThan6Characters()
    {
        $payload = [
            'email' => 'ilham.arrouf@gmail.com',
            'password' => str_random(5),
        ];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => ['The password must be at least 6 characters.'],
                ],
            ]);
    }

    public function testPasswordGreaterThan32Characters()
    {
        $payload = [
            'email' => 'ilham.arrouf@gmail.com',
            'password' => str_random(33),
        ];

        $this->json('POST', '/api/login', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => ['The password may not be greater than 32 characters.'],
                ],
            ]);
    }
}
