<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $fillable=[
		'title',
		'brand_id',
		'category_id',
		'click_count',
		'total_stock',
		'comment_count',
		'goods_cover',
		'goods_images',
		'price',
		'keywords',
		'goods_remark',
		'goods_content',
		'goods_type',
		'collect_sum',
		'is_on_sale',
		'is_free_shipping',
		'is_recommend',
		'is_new',
		'sort',
		'sales_sum',
    ];

    protected $casts=[
		'is_on_sale'=>'boolean',
		'is_free_shipping'=>'boolean',
		'is_recommend'=>'boolean',
		'is_new'=>'boolean',
		'goods_images'=>'json'
    ];

    public function goodsSkus()
    {
    	return $this->hasMany(GoodsSku::class);
    }

    public function brand()
    {
    	return $this->belongsTo(Brand::class);
    }

    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    public function setGoodsImagesAttribute($goods_images)
    {
    	if (is_array($goods_images)) {
			$this->attributes['goods_images'] = json_encode($goods_images);
		}
    }
    
    public function getGoodsImagesAttribute($goods_images)
    {
    	return json_decode($goods_images, true);
    }
}
