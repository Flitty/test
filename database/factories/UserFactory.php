<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'name' => $faker->name,
        'password' => bcrypt('QWE123qwe'),
        'birthday' => $faker->date('Y-m-d'),
        'avatar' => $faker->imageUrl(),
        'gender' => rand(0,1),
        'news_mailing' => rand(0,1),
        'biography' => $faker->text
    ];
});
