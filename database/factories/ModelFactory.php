<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

function random_float($min, $max) {
    return $min + ((rand()/getrandmax()) * ($max - $min));
}

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Models\Brewery::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->streetAddress,
        'postalCode' => $faker->postcode,
        'city' => $faker->city,
        'longitude' => random_float(5, 6.0),
        'latitude' => random_float(52.0, 53.0)
    ];
});

$factory->define(App\Models\Beer::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->userName,
        'volume' => rand(10, 33),
        'alcohol' => random_float(1.2, 14),
        'style' => $faker->firstName,
        'keg' => $faker->firstName
    ];
});
