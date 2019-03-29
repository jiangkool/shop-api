<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable=[
		'province',
		'city',
		'district',
		'address_details',
		'last_used_at'
    ];

    protected $dates=[
    	'last_used_at'
    ];

    public function getFullAddressAttribute()
    {
    	return "{$this->province}{$this->city}{$this->district}{$this->address_details}";
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
