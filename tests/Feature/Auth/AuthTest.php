<?php

namespace Tests\Feature\Auth;

use App\Domain\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testAccessTokenUser()
    {
        $user = factory(User::class)->create();

        $this->withHeaders([
                'Authorization' => 'Bearer '.$user->createToken(
                        config('app.key')
                    )->accessToken,
            ])
            ->json('GET', '/api/user')
            ->assertStatus(200)
            ->assertJson($user->only(
                'id',
                'avatar',
                'name',
                'email',
                'created_at',
                'updated_at'
            ));
    }
}
