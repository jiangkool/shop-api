<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\ModelTree;
use Encore\Admin\Traits\AdminBuilder;

class ArticleCategory extends Model
{
	use AdminBuilder, ModelTree {
        ModelTree::boot as treeBoot;
    }

    protected $fillable=[
		'title',
		'cat_alias',
		'parent_id',
		'show_in_nav',
		'order',
		'cat_desc',
    ];

    protected $casts = [
        'show_in_nav' => 'boolean',
    ];

    public function parent()
    {
    	return $this->belongsTo(ArticleCategory::class,'parent_id','id');
    }

    protected static function boot()
    {
        static::treeBoot();

    }
}
