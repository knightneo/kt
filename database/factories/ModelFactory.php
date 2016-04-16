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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
    ];
});

$factory->define(App\Models\Article::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'content' => $faker->safeEmail,
        'is_published' => 1,
        'is_deleted' => 0,
    ];
});

$factory->define(App\Models\Role::class, function (Faker\Generator $faker) {
    return [
        
    ];
});

$factory->define(App\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        
    ];
});

$factory->define(App\Models\PermissionRole::class, function (Faker\Generator $faker) {
    return [
        
    ];
});
