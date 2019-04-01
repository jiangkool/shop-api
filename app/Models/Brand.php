<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable=[
		'category_id',
		'name',
		'logo',
		'desc',
		'sort',
		'is_rec',
    ];

    protected $casts=[
    	'is_rec'=>'boolean'
    ];

    public function category()
    {
    	return $this->belongsTo(Category::class);
    }
}
