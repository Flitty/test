<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Point::class, function (Faker $faker) {
    return [
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude
    ];
});
