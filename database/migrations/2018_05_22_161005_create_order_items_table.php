<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id')->comment('门店 ID')->index();
            $table->string('order_no')->comment('交易订单号')->index();
            $table->unsignedInteger('item_id')->comment('商品 ID')->index();
            $table->string('item_type')->comment('商品类型')->index();
            $table->string('item_title')->nullable()->comment('商品名字');
            $table->string('image_url')->nullable()->comment('主图 URL');
            $table->unsignedInteger('quantity')->comment('购买数量');
            $table->decimal('unit_price')->comment('单价');
            $table->decimal('sub_total')->comment('总价');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
