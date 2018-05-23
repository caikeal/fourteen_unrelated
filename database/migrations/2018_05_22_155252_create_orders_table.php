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
        Schema::create('orders', function (Blueprint $table) {
            // Info
            $table->string('order_no')->comment('订单号')->primary();
            $table->unsignedInteger('store_id')->comment('门店 ID')->index();
            $table->unsignedInteger('user_id')->comment('用户 ID')->index();
            $table->unsignedInteger('staff_id')->default(0)->comment('服务员 ID');
            $table->string('title')->comment('订单标题');
            $table->string('status')->default('WAIT_PAY')->comment('订单状态：WAIT_PAY 待付款，COMPLETED 已完成，CANCELLED 已取消')->index();
            $table->string('payment_channel')->nullable()->comment('支付渠道');
            $table->string('order_type')->nullable()->comment('订单类型');

            // Money
            $table->decimal('total_amount')->comment('订单金额');
            $table->decimal('reduce_amount')->default(0)->comment('减免金额');
            $table->decimal('amount_receivable')->comment('应收金额');
            $table->decimal('received_amount')->default(0)->comment('实收金额');

            // Cancel Order
            $table->string('cancelled_by')->nullable()->comment('订单关闭方式：NOT_PAID 超时未支付关闭，MEMBER 会员手动关闭，MERCHANT 商家手动关闭');

            // Refund
            $table->string('refund_status')->nullable()->comment('状态：REFUNDING 退款中，SUCCEEDED 退款成功，FAILED 退款失败');

            // Time
            $table->timestamp('paid_at')->nullable()->comment('支付时间');
            $table->timestamp('estimate_cancel_at')->nullable()->comment('预估取消时间');
            $table->timestamp('cancelled_at')->nullable()->comment('取消时间');
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
        Schema::dropIfExists('orders');
    }
}
