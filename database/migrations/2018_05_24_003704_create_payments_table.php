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

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->string('payment_no')->comment('支付流水号')->primary();
            $table->unsignedInteger('store_id')->comment('门店 ID')->index();
            $table->string('order_no')->comment('交易订单号')->index();
            $table->string('channel')->comment('支付渠道')->index();
            $table->string('transaction_no')->nullable()->comment('渠道流水号')->index();
            $table->decimal('amount_receivable')->comment('应收金额');
            $table->decimal('received_amount')->default(0)->comment('实收金额');
            $table->decimal('refunded_amount')->default(0)->comment('已退金额');
            $table->text('comment')->nullable()->comment('备注');
            $table->string('status')->default('PROCESSING')->comment('状态：PROCESSING 支付中，SUCCEEDED 支付成功，FAILED 支付失败，CLOSED 已关闭');
            $table->string('failed_reason')->nullable()->comment('支付失败原因');
            $table->timestamp('finished_at')->nullable()->comment('结束时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
