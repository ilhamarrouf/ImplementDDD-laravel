<?php

namespace Tests\Feature;

use App\Domain\Topic\Entities\Topic;
use App\Domain\User\Entities\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TopicTest extends TestCase
{
    public function testUserCanSeeTopicLists()
    {
        $this->json('GET', '/api/topics')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ]);
    }

    public function testUserCanSeeTopicDetails()
    {
        $topic = factory(Topic::class)->create();

        $this->json('GET', '/api/topics/'.$topic->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ]);
    }

    public function testCreatedSuccessfully()
    {
        $faker = Factory::create();
        $user = factory(User::class)->create();

        $payload = [
            'name' => $faker->sentence(2),
        ];

        $this->actingAs($user, 'api')
            ->json('POST', '/api/topics', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ]);
    }

    public function testUpdatedSuccessfully()
    {
        $faker = Factory::create();
        $user = factory(User::class)->create();
        $topic = factory(Topic::class)->create();

        $payload = [
            'id' => $topic->id,
            'name' => $faker->sentence(2),
        ];

        $this->actingAs($user, 'api')
            ->json('PATCH', '/api/topics/'.$topic->id, $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ]);
    }

    public function testDeletedSuccessfully()
    {
        $user = factory(User::class)->create();
        $topic = factory(Topic::class)->create();

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/topics/'.$topic->id)
            ->assertStatus(200)
            ->assertJson([
                'message' => trans('topic.deleted')
            ]);
    }
}
