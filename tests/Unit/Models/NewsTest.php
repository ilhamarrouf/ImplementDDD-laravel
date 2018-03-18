<?php

namespace Tests\Unit\Models;

use App\Domain\News\Entities\News;
use App\Domain\Topic\Entities\Topic;
use App\Domain\User\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NewsTest extends TestCase
{
    public function testBelongsToCreator()
    {
        $news = factory(News::class)->create();

        $this->assertInstanceOf(User::class, $news->creator);
    }

    public function testHasManyTopicsWithMorphRelation()
    {
        $news = factory(News::class)->create();
        $news->topics()->sync(factory(Topic::class, 2)->create()->pluck('id'));

        $this->assertInstanceOf(Collection::class, $news->topics);
        $this->assertInstanceOf(Topic::class, $news->topics->first());
    }
}
