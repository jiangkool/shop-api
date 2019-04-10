<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            //0下单赠送1 指定发放 2 免费领取 3线下发放
            $table->string('receive_type')->default('free_collection'); 
            $table->string('type')->default('reduce');
            $table->decimal('min_condition_money',10,2);
            $table->decimal('type_value',10,2);
            $table->unsignedInteger('amount')->default(0);
            $table->unsignedInteger('receive_amount')->default(0);
            $table->unsignedInteger('used_amount')->default(0);
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('start_use_time');
            $table->dateTime('end_use_time');
            $table->boolean('status')->default(0);
            $table->string('use_scope')->default('whole_store');
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
        Schema::dropIfExists('coupons');
    }
}
