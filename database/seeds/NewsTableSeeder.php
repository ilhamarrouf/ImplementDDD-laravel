<?php

use App\Domain\News\Entities\News;
use App\Domain\Topic\Entities\Topic;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(News::class, 10)->create()->each(function ($news) {
            $news->topics()->sync(Topic::inRandomOrder()->take(2)->pluck('id'));
        });
    }
}
