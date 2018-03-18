<?php

namespace Tests\Unit\Policy;

use App\Domain\News\Entities\News;
use App\Domain\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsPolicyTest extends TestCase
{
    public function testUserCanUpdateOwnNews()
    {
        $user = factory(User::class)->create();
        $news = factory(News::class)->create([
            'creator_id'=> $user->id,
        ]);

        $this->assertTrue($user->can('update', $news));
    }

    public function testUserCanNotUpdateOtherUserNews()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $news = factory(News::class)->create([
            'creator_id'=> $owner->id,
        ]);

        $this->assertFalse($user->can('update', $news));
    }

    public function testUserCanDeleteOwnNews()
    {
        $user = factory(User::class)->create();
        $news = factory(News::class)->create([
            'creator_id'=> $user->id,
        ]);

        $this->assertTrue($user->can('delete', $news));
    }

    public function testUserCanNotDeleteOtherUserNews()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $news = factory(News::class)->create([
            'creator_id'=> $owner->id,
        ]);

        $this->assertFalse($user->can('delete', $news));
    }
}
