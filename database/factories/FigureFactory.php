<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Figure::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'perimeter' => $faker->numberBetween(0, 9999)
    ];
});
