<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSku extends Model
{
    protected $fillable=[
		'sku_title',
		'goods_id',
		'stock',
		'unit_price',
		'market_price',
		'weight',
		'volume',
		'goods_remark',
		'give_integral',
    ];

    public function Goods()
    {
    	return $this->belongsTo(Goods::class);
    }
    
}
