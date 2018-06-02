<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Models;

use App\Libraries\Utils\Math;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Payment extends Model
{
    const STATUS_PROCESSING = 'PROCESSING';  // 支付中
    const STATUS_SUCCEEDED  = 'SUCCEEDED';   // 支付成功
    const STATUS_FAILED     = 'FAILED';      // 支付失败
    const STATUS_CLOSED     = 'CLOSED';      // 已关闭

    const WECHAT_SCAN_PAYMENAT_CHANNEL = 'WECHAT_SCAN_PAY'; // 微信扫码
    const ALI_SCAN_PAYMENAT_CHANNEL    = 'ALI_SCAN_PAY'; // 支付宝扫码

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'payment_no';

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
        'finished_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'store_id' => 'integer',
    ];

    protected $fillable = [
        'payment_no',
        'store_id',
        'trade_order_no',
        'channel',
        'transaction_no',
        'amount_receivable',
        'received_amount',
        'refunded_amount',
        'comment',
        'status',
        'failed_reason',
        'finished_at',
    ];

    public function tradeOrder()
    {
        return $this->belongsTo(Order::class, 'order_no', 'order_no');
    }

    /**
     * 生成支付单号.
     *
     * @return string
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public static function generatePaymentNo()
    {
        $sn = Math::generateSn('11');

        /* 到数据库里查找是否已存在 */
        try {
            self::findOrFail($sn);
        } catch (ModelNotFoundException $e) {
            return $sn;
        }

        /* 如果有重复的，则重新生成 */

        return self::generatePaymentNo();
    }
}
