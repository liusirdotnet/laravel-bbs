<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Topic::class, function (Faker $faker) {
    $sentence = $faker->sentence();
    $createAt = $faker->dateTimeThisMonth();
    $updateAt = $faker->dateTimeThisMonth($createAt);

    return [
        'title'      => $sentence,
        'body'       => $faker->text(),
        'excerpt'    => $sentence,
        'created_at' => $createAt,
        'updated_at' => $updateAt,
    ];
});
