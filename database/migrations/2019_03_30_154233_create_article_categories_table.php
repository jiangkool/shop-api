<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('cat_alias');
            $table->unsignedInteger('parent_id')->default(0);
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->boolean('show_in_nav')->default(0);
            $table->integer('order')->default(0);
            $table->text('cat_desc')->nullable();
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
        Schema::dropIfExists('article_categories');
    }
}
