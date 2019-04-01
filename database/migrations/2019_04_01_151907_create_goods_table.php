<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
             $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedInteger('click_count')->default(0);
            $table->unsignedInteger('total_stock')->default(0);
            $table->unsignedInteger('comment_count')->default(0);
            $table->decimal('price',10,2)->default(0);
            $table->string('keywords');
            $table->text('goods_cover');
            $table->text('goods_images');
            $table->text('goods_remark')->nullable();
            $table->longText('goods_content');
            $table->integer('goods_type')->default(0);
            $table->unsignedInteger('collect_sum')->default(0);
            $table->boolean('is_on_sale')->default(0);
            $table->boolean('is_free_shipping')->default(0);
            $table->boolean('is_recommend')->default(0);
            $table->boolean('is_new')->default(0);
            $table->integer('sort')->default(0);
            $table->unsignedInteger('sales_sum')->default(0);
            $table->timestamps();
        });

        Schema::create('goods_skus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->unsignedInteger('goods_id');
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('cascade');
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('unit_price',10,2)->default(0);
            $table->decimal('market_price',10,2)->default(0);
            $table->unsignedInteger('weight')->default(0);
            $table->decimal('volume',10,2)->default(0);
            $table->text('goods_remark')->nullable();
            $table->unsignedInteger('give_integral')->default(0);
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
        Schema::dropIfExists('goods');
        Schema::dropIfExists('goods_skus');
    }
}
