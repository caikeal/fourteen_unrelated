<?php

/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Controllers\Staff\Api\V1;

use App\Http\Controllers\Staff\Api\BaseController;
use App\Http\Requests\Staff\ScanOrderRequest;
use App\Services\Staff\OrderService;

class OrderController extends BaseController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function scan(ScanOrderRequest $request)
    {
        // 扫取的二维码
        $code = $request->input('code');
        // 金额
        $amount = $request->input('amount');
        // 获取使用设备用户
        $staff = $request['staff'];

        // 创建订单
        $order = $this->orderService->createOrder(['staff' => $staff, 'amount' => $amount]);

        // 创建支付
        $order = $this->orderService->createPayment(['order' => $order, 'code' => $code, 'company' => $company]);

        return $order;
    }
}
