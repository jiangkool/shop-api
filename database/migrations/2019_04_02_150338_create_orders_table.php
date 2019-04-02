<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company')->unique();
            $table->decimal('start_fee',10,2);
            $table->decimal('unit_fee',10,2);
            $table->boolean('closed')->default(0);
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('product_type')->default(0);
            $table->decimal('total_money',10,2);
            $table->unsignedInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->dateTime('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_no')->nullable();
            $table->string('refund_no')->nullable();
            $table->string('refund_status')->nullable();
            $table->unsignedInteger('ship_id')->nullable();
            $table->foreign('ship_id')->references('id')->on('ships')->onDelete('set null');
            $table->string('ship_status')->nullable();
            $table->text('bark')->nullable();
            $table->boolean('closed')->default(0);
            $table->text('extra')->nullable();
            $table->timestamps();
        });


        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedInteger('goods_id')->nullable();
            $table->foreign('goods_id')->references('id')->on('goods')->onDelete('set null');
            $table->unsignedInteger('goods_sku_id')->nullable();
            $table->foreign('goods_sku_id')->references('id')->on('goods_skus')->onDelete('set null');
            $table->unsignedInteger('amount')->default(0);
            $table->decimal('price',10,2);
            $table->unsignedInteger('rating')->nullable();
            $table->text('review')->nullable();
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('ships');
        Schema::dropIfExists('order_items');
    }
}
