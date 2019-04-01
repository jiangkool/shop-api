<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_user_id')->nullable();
            $table->foreign('admin_user_id')->references('id')->on('admin_users')->onDelete('set null');
            $table->unsignedInteger('article_category_id');
            $table->foreign('article_category_id')->references('id')->on('article_categories')->onDelete('cascade');
            $table->string('title');
            $table->longText('content');
            $table->string('author')->nullable();
            $table->string('keywords')->nullable();
            $table->string('file_url')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('click')->default(0);
            $table->text('thumb')->nullable();
            $table->boolean('is_published')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
