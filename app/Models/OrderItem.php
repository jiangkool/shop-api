<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable=[
		'amount',
		'price',
		'rating',
		'reviewed_at',
    ];

    protected $casts=[
    	'reviewed_at'
    ];

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function goods()
    {
    	return $this->belongsTo(Goods::class);
    }

    public function goods_sku()
    {
    	return $this->belongsTo(GoodsSku::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
