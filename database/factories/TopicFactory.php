<?php

use App\Domain\Topic\Entities\Topic;
use Faker\Generator as Faker;

$factory->define(Topic::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
