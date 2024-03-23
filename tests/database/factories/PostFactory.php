<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Snortlin\NanoId\NanoId;
use Tests\Fixtures\CustomCastNanoIdPost;
use Tests\Fixtures\CustomNanoIdPost;
use Tests\Fixtures\CustomNanoIdRouteBoundPost;
use Tests\Fixtures\MultipleNanoIdPost;
use Tests\Fixtures\MultipleNanoIdRouteBoundPost;
use Tests\Fixtures\Post;
use Tests\Fixtures\NanoIdRouteBoundPost;

$factory->define(CustomCastNanoIdPost::class, function (Faker $faker) {
    return [
        'custom_nanoId' => NanoId::nanoId(),
        'title' => $faker->sentence,
    ];
});

$factory->define(CustomNanoIdPost::class, function (Faker $faker) {
    return [
        'custom_nanoId' => NanoId::nanoId(),
        'title' => $faker->sentence,
    ];
});

$factory->define(MultipleNanoIdPost::class, function (Faker $faker) {
    return [
        'uuid' => NanoId::nanoId(),
        'custom_nanoId' => NanoId::nanoId(),
        'title' => $faker->sentence,
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    return [
        'uuid' => NanoId::nanoId(),
        'title' => $faker->sentence,
    ];
});

$factory->define(CustomNanoIdRouteBoundPost::class, function (Faker $faker) {
    return [
        'uuid' => NanoId::nanoId(),
        'custom_nanoId' => NanoId::nanoId(),
        'title' => $faker->sentence,
    ];
});

$factory->define(NanoIdRouteBoundPost::class, function (Faker $faker) {
    return [
        'uuid' => NanoId::nanoId(),
        'custom_nanoId' => NanoId::nanoId(),
        'title' => $faker->sentence,
    ];
});

$factory->define(MultipleNanoIdRouteBoundPost::class, function (Faker $faker) {
    return [
        'uuid' => NanoId::nanoId(),
        'custom_nanoId' => NanoId::nanoId(),
        'title' => $faker->sentence,
    ];
});
