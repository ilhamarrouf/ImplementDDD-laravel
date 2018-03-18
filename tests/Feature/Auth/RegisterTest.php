<?php

namespace Tests\Feature\Auth;

use App\Domain\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testRegisterSuccessfully()
    {
        $payload = [
            'name' => 'Muhamad Ilham Arrouf',
            'email' => 'ilham.arrouf@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $this->json('POST', '/api/register', $payload)
            ->assertStatus(201)
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

    public function testRequiredNameEmailAndPassword()
    {
        $this->json('POST', '/api/register')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
            ]);
    }

    public function testDontHavePasswordConfirmation()
    {
        $payload = [
            'name' => 'Muhamad Ilham Arrouf',
            'email' => 'ilham.arrouf@gmail.com',
            'password' => '123456',
        ];

        $this->json('POST', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => ['The password confirmation does not match.'],
                ],
            ]);
    }

    public function testEmailHasBeenTaken()
    {
        $user = factory(User::class)->create();

        $payload = [
            'name' => 'Muhamad Ilham Arrouf',
            'email' => $user->email,
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $this->json('POST', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
            ]);
    }

    public function testWrongEmailFormat()
    {
        $payload = [
            'name' => 'Muhamad Ilham Arrouf',
            'email' => str_random(10),
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $this->json('POST', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['The email must be a valid email address.'],
                ],
            ]);
    }

    public function testNameLessThan3Characters()
    {
        $payload = [
            'name' => str_random(2),
            'email' => 'ilham.arrouf@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $this->json('POST', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name must be at least 3 characters.'],
                ],
            ]);
    }

    public function testNameGreaterThan20Characters()
    {
        $payload = [
            'name' => str_random(21),
            'email' => 'ilham.arrouf@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        $this->json('POST', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name may not be greater than 20 characters.'],
                ],
            ]);
    }

    public function testPasswordLessThan6Characters()
    {
        $payload = [
            'name' => 'Muhamad Ilham Arrouf',
            'email' => 'ilham.arrouf@gmail.com',
            'password' => $password = str_random(5),
            'password_confirmation' => $password,
        ];

        $this->json('POST', '/api/register', $payload)
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
            'name' => 'Muhamad Ilham Arrouf',
            'email' => 'ilham.arrouf@gmail.com',
            'password' => $password = str_random(33),
            'password_confirmation' => $password,
        ];

        $this->json('POST', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => ['The password may not be greater than 32 characters.'],
                ],
            ]);
    }
}
