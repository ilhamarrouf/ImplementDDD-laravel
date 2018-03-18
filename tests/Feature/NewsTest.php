<?php

namespace Tests\Feature;

use App\Domain\News\Entities\News;
use App\Domain\Topic\Entities\Topic;
use App\Domain\User\Entities\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsTest extends TestCase
{
    public function testUserCanSeeNewsLists()
    {
        $this->json('GET', '/api/news')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'creator',
                        'title',
                        'slug',
                        'body',
                        'status',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ]);
    }

    public function testUserCanSeeNewsListsWithTopics()
    {
        $this->json('GET', '/api/news?with=topics')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'creator',
                        'title',
                        'slug',
                        'body',
                        'status',
                        'topics' => [
                            '*' => [
                                'id',
                                'name',
                                'created_at',
                                'updated_at',
                                'deleted_at',
                            ],
                        ],
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ]);
    }

    public function testUserCanSeeNewsDetails()
    {
        $news = factory(News::class)->create();

        $this->json('GET', '/api/news/'.$news->slug)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'creator',
                    'title',
                    'slug',
                    'body',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ]);
    }

    public function testUserCanSeeNewsDetailsWithTopics()
    {
        $news = factory(News::class)->create();
        $news->topics()->sync(factory(Topic::class, 2)->create()->pluck('id'));

        $this->json('GET', '/api/news/'.$news->slug.'?with=topics')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'creator',
                    'title',
                    'slug',
                    'body',
                    'status',
                    'topics' => [
                        '*' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                        ],
                    ],
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
        $topics = factory(Topic::class, 2)->create();

        $payload = [
            'title' => $faker->sentence(5),
            'body' => $faker->text(800),
            'status' => $faker->randomElement(['publish', 'draft', 'delete']),
            'topics' => $topics->pluck('id'),
        ];

        $this->actingAs($user, 'api')
            ->json('POST', '/api/news', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'creator',
                    'title',
                    'slug',
                    'body',
                    'status',
                    'topics' => [
                        '*' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                        ],
                    ],
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ]);
    }

    public function testUpdatedSuccessfully()
    {
        $faker = Factory::create();
        $news = factory(News::class)->create();
        $topics = factory(Topic::class)->create();

        $payload = [
            'id' => $news->id,
            'title' => $faker->sentence(5),
            'body' => $faker->text(800),
            'status' => $faker->randomElement(['publish', 'draft', 'delete']),
            'topics' => $topics->pluck('id'),
        ];

        $this->actingAs($news->creator, 'api')
            ->json('PATCH', '/api/news/'.$news->slug, $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'creator',
                    'title',
                    'slug',
                    'body',
                    'status',
                    'topics' => [
                        '*' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at',
                            'deleted_at',
                        ],
                    ],
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ]);
    }

    public function testDeletedSuccessfully()
    {
        $news = factory(News::class)->create([
            'status' => 'publish',
        ]);

        $this->actingAs($news->creator, 'api')
            ->json('DELETE', '/api/news/'.$news->slug)
            ->assertStatus(200)
            ->assertJson([
                'message' => trans('news.deleted'),
            ]);

        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'status' => News::STATUS_DELETE,
        ]);
    }

    public function testRequiredTitleBodyStatus()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/news')
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'title' => ['The title field is required.'],
                    'body' => ['The body field is required.'],
                    'status' => ['The status field is required.'],
                ],
            ]);
    }
}
