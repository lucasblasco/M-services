<?php

use Faker\Generator as Faker;

$factory->define(App\DocumentType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement($array = array ('DNI','LC','Pasaporte')),
    ];
});
