<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InternalException;

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

    //Increase stock
    public function increaseStock($amount)
    {
        if ($amount<0) {
        	throw new InternalException('库存数量错误！');
        }
        $this->increment('stock', $amount);
    }

    //Decrease stock
    public function decreaseStock($amount)
    {
        if ($amount<0) {
        	throw new InternalException('库存数量错误！');
        }
        return $this->newQuery()->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
    }
    
}
