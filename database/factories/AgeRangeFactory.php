<?php

use Faker\Generator as Faker;

$factory->define(App\AgeRange::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement($array = array ('Menores de 18', 'Entre 18 y 35', 'Entre 36 y 60', 'Mayores de 60')),
    ];
});
