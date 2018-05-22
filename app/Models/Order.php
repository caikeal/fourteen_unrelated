<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_WAIT_PAY  = 'WAIT_PAY';                // 待付款
    const STATUS_COMPLETED = 'COMPLETED';               // 已完成
    const STATUS_CANCELLED = 'CANCELLED';               // 已取消

    const CANCELLED_BY_NOT_PAID = 'NOT_PAID';           // 超时未支付关闭
    const CANCELLED_BY_MEMBER = 'MEMBER';             // 会员手动关闭
    const CANCELLED_BY_MERCHANT = 'MERCHANT';           // 商家手动关闭

    const ORDER_TYPE_OFFLINE_SCAN = 'OFFLINE_SCAN';    // 线下扫码枪

    /**
     * 退款渠道类型.
     */
    const PAYMENT_CHANNEL_WECHAT_PAY = 'WECHAT_PAY'; //微信
    const PAYMENT_CHANNEL_CASH       = 'CASH'; //线下退款:现金,银行卡或者其他不再系统内的渠道

    public static $status = [
        self::STATUS_WAIT_PAY,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_no';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $dates = [
        'estimate_cancel_at',
        'paid_at',
        'cancelled_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'store_id' => 'integer',
    ];

    protected $fillable = [
        'order_no',
        'user_id',
        'title',
        'store_id',
        'status',
        'order_type',
        'payment_channel',
        'total_amount',
        'reduce_amount',
        'amount_receivable',
        'received_amount',
        'cancelled_by',
        'estimate_cancel_at',
        'paid_at',
        'cancelled_at',
        'refund_status',
    ];
}