<?php

/** @var $factory \Illuminate\Database\Eloquent\Factory */
use App\Ccu\General\Category;

function categoryRandomElement($category)
{
    return Category::getCategories($category)->random()->getAttribute('id');
}

$factory->define(\App\Ccu\User\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'email' => $faker->email,
        'nickname' => $faker->name,
    ];
});

$factory->define(\App\Ccu\Course::class, function (Faker\Generator $faker) {
    return [
        'semester_id' => categoryRandomElement('semester'),
        'code' => $faker->numberBetween(1000000, 9999999),
        'department_id' => categoryRandomElement('department'),
        'name' => $faker->name,
    ];
});

$factory->define(\App\Ccu\General\Attachment::class, function (Faker\Generator $faker) {
    return [
        'sha256' => $faker->sha256,
        'info' => ['name' => $faker->name, 'size' => $faker->numberBetween(1, 1024)],
        'path' => $faker->sentence,
        'downloads' => $faker->numberBetween(0, 65536),
        'created_at' => $faker->dateTime,
    ];
});

$factory->define(\App\Ccu\General\Comment::class, function (Faker\Generator $faker) {
    return [
        'content' => $faker->text,
        'anonymous' => $faker->boolean(),
    ];
});

$factory->define(\App\Ccu\General\Like::class, function (Faker\Generator $faker) {
    return [
        'created_at' => $faker->dateTime,
    ];
});
