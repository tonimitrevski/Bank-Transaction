<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'country_id' => mt_rand(1, 2),
        'user_id' => mt_rand(0, 200),
        'amount' => mt_rand(0, 10000),
        'type' => mt_rand(0, 1),
    ];
});
