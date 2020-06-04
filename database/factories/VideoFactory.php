<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Models\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) use ($factory) {
    return [
        'title' => $faker->text(180),
        'description' => $faker->sentences(3),
        'uploaded_by' => $factory->create(User::class)->id
    ];
});
