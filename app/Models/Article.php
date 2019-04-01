<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable=[
		'article_category_id',
		'title',
		'user_id',
		'content',
		'author',
		'keywords',
		'file_url',
		'description',
		'click',
		'thumb',
		'is_published',
    ];

    protected $casts=[
    	'is_published'=>'boolean',
    ];

    public function articleCategory()
    {
    	return $this->belongsTo(ArticleCategory::class);
    }

    public function adminUser()
    {
    	return $this->belongsTo(\Encore\Admin\Auth\Database\Administrator::class,'admin_user_id','id');
    }
}
