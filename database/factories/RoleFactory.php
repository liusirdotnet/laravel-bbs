<?php

use App\Models\Role;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {

    $now = Carbon::now()->toDateTimeString();

    return [
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
