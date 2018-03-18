<?php

namespace Tests\Unit\Models;

use App\Domain\News\Entities\News;
use App\Domain\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testHasManyNews()
    {
        $user = factory(User::class)->create();
        $news = factory(News::class, 2)->create([
            'creator_id' => $user->id,
        ]);

        $this->assertInstanceOf(Collection::class, $user->news);
        $this->assertInstanceOf(News::class, $user->news->first());
    }
}
