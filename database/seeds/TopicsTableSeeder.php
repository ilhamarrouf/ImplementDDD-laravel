<?php

use App\Domain\Topic\Entities\Topic;
use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Topic::class, 10)->create();
    }
}
