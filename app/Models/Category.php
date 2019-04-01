<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;

class Category extends Model
{
	use AdminBuilder, ModelTree {
        ModelTree::boot as treeBoot;
    }

    protected $fillable=[
		'title',
		'parent_id',
		'order',
    ];

    public function parent()
    {
    	return $this->belongsTo(Category::class,'parent_id','id');
    }

    protected static function boot()
    {
        static::treeBoot();
    }
}
