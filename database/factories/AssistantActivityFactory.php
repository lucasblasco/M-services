<?php

use Faker\Generator as Faker;

$factory->define(App\AssistantActivity::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement($array = array ('Profesional', 'No profesional', 'Ambos')),
    ];
});
