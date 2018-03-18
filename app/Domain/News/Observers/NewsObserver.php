<?php

namespace App\Domain\News\Observers;

use App\Domain\News\Entities\News;

class NewsObserver
{
    public function saving(News $news) : void
    {
        $news->slug = str_slug($news->title).'-'.str_random(3);
    }
}
