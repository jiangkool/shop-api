<?php

use Faker\Generator as Faker;

use Carbon\Carbon;

$factory->define(App\Models\Address::class, function (Faker $faker) {
    return [
        'province'=>'广东省',
        'city'=>'广州市',
        'district'=>'天河区',
        'address_details'=>'名和大道1010号',
        'last_used_at'=>Carbon::now()
    ];
});
