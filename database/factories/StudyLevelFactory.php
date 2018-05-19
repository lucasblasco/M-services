<?php

use Faker\Generator as Faker;

$factory->define(App\StudyLevel::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});
