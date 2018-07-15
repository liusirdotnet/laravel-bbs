<?php

use App\Models\Permission;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {

    $now = Carbon::now()->toDateTimeString();

    return [
        'created_at' => $now,
        'updated_at' => $now,
    ];
});
