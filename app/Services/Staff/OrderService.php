<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services\Staff;

use App\Exceptions\PaymentAuthCodeErrorParseException;
use App\Exceptions\PaymentFailException;
use App\Exceptions\PaymentSettingNotExistException;
use App\Exceptions\PaymentWaitConfirmedException;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Overtrue\LaravelWeChat\Facade as EasyWechat;

class OrderService extends BaseService
{
    /**
     * 创建订单.
     *
     * @param $params ['user', 'amount']
     *
     * @return mixed
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public function createOrder($params)
    {
        // 创建订单
        $staff       = $params['staff'];
        $amount      = $params['amount'];
        $orderNo     = Order::generateOrderNo();
        $orderParams = [
            'order_no'          => $orderNo,
            'staff_id'          => $staff->id,
            'store_id'          => $staff->store_id,
            'title'             => '到店消费',
            'total_amount'      => $amount,
            'reduce_amount'     => 0,
            'amount_receivable' => $amount,
            'status'            => Order::STATUS_WAIT_PAY,
        ];
        $order = Order::create($orderParams);

        return $order;
    }

    /**
     * 创建支付.
     *
     * @param $params ['code', 'order']
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public function createPayment($params)
    {
        $payType = $this->checkPaymentCategory($params['code']);
        switch ($payType) {
            case Payment::WECHAT_SCAN_PAYMENAT_CHANNEL:
                return $this->handleWechatpay($params);
                break;
            case Payment::ALI_SCAN_PAYMENAT_CHANNEL:
                return $this->handleAlipay($params);
                break;
            default:
                throw new PaymentAuthCodeErrorParseException('非支持的付款码');
                break;
        }
    }

    /**
     * 验证支付类型.
     *
     * @param $authCode
     *
     * @return string
     *
     * @author Caikeal <caikeal@qq.com>
     */
    protected function checkPaymentCategory($authCode)
    {
        if (preg_match('/^1[0-5][0-9]+$/', $authCode)) {
            // 微信是10、11、12、13、14、15开头的数字
            return Payment::WECHAT_SCAN_PAYMENAT_CHANNEL;
        } elseif (preg_match('/^(2[5-9]|30)[0-9]+$/', $authCode)) {
            // 支付宝是25~30开头的数字
            return Payment::ALI_SCAN_PAYMENAT_CHANNEL;
        } else {
            throw new PaymentAuthCodeErrorParseException('非支持的付款码');
        }
    }

    /**
     * 微信条码支付.
     *
     * @param $params
     *
     * @return mixed
     *
     * @author Caikeal <caikeal@qq.com>
     *
     * @throws \Exception
     */
    protected function handleWechatpay($params)
    {
        $wechatPay     = EasyWechat::payment();
        $paymentNo     = Payment::generatePaymentNo(); // 微信支付单号
        $wxPaymentInfo = $wechatPay->pay([
            'body'         => $params['order']->title,
            'out_trade_no' => $paymentNo,
            'total_fee'    => (int) ($params['order']->amount_receivable * 100),
            'auth_code'    => $params['code'],
        ]);

        // 微信支付通信失败
        if ($wxPaymentInfo['return_code'] == 'FAIL') {
            payment_log($wxPaymentInfo);
            // 创建支付信息
            Payment::create([
                'payment_no'        => $paymentNo,
                'trade_order_no'    => $params['order']->order_no,
                'channel'           => Payment::WECHAT_SCAN_PAYMENAT_CHANNEL,
                'amount_receivable' => $params['order']->amount_receivable * 100,
                'failed_reason'     => $wxPaymentInfo['return_msg'],
                'status'            => Payment::STATUS_FAILED,
                'comment'           => json_encode($wxPaymentInfo),
            ]);
            // 更新订单
            $params['order']->status         = Order::STATUS_CANCELLED;
            $params['order']->cancelled_type = Order::CANCELLED_BY_NOT_PAID;
            $params['order']->cancelled_at   = Carbon::now();
            $params['order']->update();
            throw new PaymentFailException('微信支付系统繁忙，请稍后再试：'.$wxPaymentInfo['return_msg']);
        }

        // 处理支付失败的情况
        if ($wxPaymentInfo['return_code'] == 'FAIL') {
            if ($wxPaymentInfo['err_code'] == 'SYSTEMERROR'
                || $wxPaymentInfo['err_code'] == 'BANKERROR'
                ||$wxPaymentInfo['err_code'] == 'USERPAYING') {
                // 创建支付信息
                Payment::create([
                    'payment_no'        => $paymentNo,
                    'trade_order_no'    => $params['order']->order_no,
                    'channel'           => Payment::WECHAT_SCAN_PAYMENAT_CHANNEL,
                    'amount_receivable' => $params['order']->amount_receivable * 100,
                    'failed_reason'     => $wxPaymentInfo['err_code'],
                    'status'            => Payment::STATUS_PROCESSING,
                    'comment'           => json_encode($wxPaymentInfo),
                ]);
                // 需要等待查询结果
                throw new PaymentWaitConfirmedException('支付等待确认');
            }
            payment_log($wxPaymentInfo);
            // 创建支付信息
            Payment::create([
                    'payment_no'        => $paymentNo,
                    'trade_order_no'    => $params['order']->order_no,
                    'channel'           => Payment::WECHAT_SCAN_PAYMENAT_CHANNEL,
                    'amount_receivable' => $params['order']->amount_receivable * 100,
                    'failed_reason'     => $wxPaymentInfo['err_code'],
                    'status'            => Payment::STATUS_FAILED,
                    'comment'           => json_encode($wxPaymentInfo),
                ]);
            // 更新订单
            $params['order']->status         = Order::STATUS_CANCELLED;
            $params['order']->cancelled_type = Order::CANCELLED_BY_NOT_PAID;
            $params['order']->cancelled_at   = Carbon::now();
            $params['order']->update();
            // 支付失败
            throw new PaymentFailException('微信支付失败，错误码：'.$wxPaymentInfo['err_code'].', 错误描述：'.$wxPaymentInfo['err_code_des']);
        }

        // 支付成功
        $payment = Payment::create([
            'payment_no'        => $paymentNo,
            'trade_order_no'    => $params['order']->order_no,
            'channel'           => Payment::WECHAT_SCAN_PAYMENAT_CHANNEL,
            'transaction_no'    => $wxPaymentInfo['transaction_id'],
            'amount_receivable' => $params['order']->amount_receivable * 100,
            'received_amount'   => $wxPaymentInfo['total_fee'],
            'finished_at'       => Carbon::createFromFormat('YmdHis', $wxPaymentInfo['time_end']),
            'status'            => Payment::STATUS_SUCCEEDED,
        ]);
        // 更新订单
        $params['order']->status          = Order::STATUS_COMPLETED;
        $params['order']->received_amount = accuracy_number($payment->received_amount / 100, 2);
        $params['order']->payment_channel = Order::PAYMENT_CHANNEL_WECHAT_PAY;
        $params['order']->paid_at         = Carbon::now();
        $params['order']->update();

        return $params['order'];
    }

    /**
     * 支付宝扫码支付.
     *
     * @param $params
     *
     * @author Caikeal <caikeal@qq.com>
     */
    protected function handleAlipay($params)
    {
        // todo alipay 支付
        throw new PaymentSettingNotExistException('支付宝支付设置不存在');
    }

    /**
     * 查看订单支付状态
     *
     * @param $orderNo
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     *
     * @author Caikeal <caikeal@qq.com>
     */
    public function checkPayment($orderNo)
    {
        $order = Order::where('order_no', $orderNo)->first();
        if (!$order) {
            throw new ModelNotFoundException('订单不存在');
        }
        // 非待支付，直接返回结果
        if ($order->status != Order::STATUS_WAIT_PAY) {
            return $order;
        }
        $payment = $order->payments()->first();
        if (!$payment) {
            throw new ModelNotFoundException('支付失败');
        }
        switch ($payment->channel) {
            case Payment::WECHAT_SCAN_PAYMENAT_CHANNEL:
                $order = $this->checkWxPayment($order, $payment);
                break;
            case Payment::ALI_SCAN_PAYMENAT_CHANNEL:
                $order = $this->checkAliPayment($order, $payment);
                break;
            default:
                break;
        }

        return $order;
    }

    /**
     * 检查微信支付.
     *
     * @param $order
     * @param $payment
     *
     * @return mixed
     *
     * @author Caikeal <caikeal@qq.com>
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected function checkWxPayment($order, $payment)
    {
        $wechatPay = EasyWechat::payment();
        $result    = $wechatPay->order->queryByOutTradeNumber($payment->payment_no);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            // 查询成功！
            if ($result['trade_state'] == 'SUCCESS') { // 交易成功
                $payment->received_amount = $result['total_fee'];
                $payment->finished_at     = Carbon::createFromFormat('YmdHis', $result['time_end']);
                $payment->status          = Payment::STATUS_SUCCEEDED;
                $payment->update();
                // 更新交易结果
                $order->received_amount = accuracy_number($result['total_fee'] / 100, 2);
                $order->status          = Order::STATUS_COMPLETED;
                $order->payment_channel = Order::PAYMENT_CHANNEL_WECHAT_PAY;
                $order->paid_at         = Carbon::now();
                $order->update();
            }
        }

        return $order;
    }

    protected function checkAliPayment($order, $payment)
    {
        // todo alipay 支付检查
        throw new PaymentSettingNotExistException('支付宝支付设置不存在');
    }
}
