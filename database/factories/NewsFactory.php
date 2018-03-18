<?php

use App\Domain\News\Entities\News;
use App\Domain\User\Entities\User;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
    return [
        'creator_id' => User::inRandomOrder()->first()->id,
        'title' => $title = $faker->sentence(5),
        'slug' => str_slug($title).'-'.str_random(3),
        'body' => $faker->text(800),
        'status' => $faker->randomElement(['publish', 'draft', 'delete']),
    ];
});
