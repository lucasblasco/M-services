<?php

use Faker\Generator as Faker;

$factory->define(App\Person::class, function (Faker $faker) {
   /* return [
        'name' => $faker->name,
        'surname' => $faker->lastName,
        'birth_date' => $faker->date,
        'document_type_id' => $faker->1,
        'study_level_id' => $faker->1,
        'document_number' => $faker->unique()->randomNumber(),
        'phone' => $faker->phoneNumber,
        'cellphone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'user_id' => $faker->1
    ];*/
});
