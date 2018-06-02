<?php
/*
 * This file is part of the caikeal/fourteen_unrelated .
 *
 * (c) caikeal <caiyuezhang@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
Route::group(['prefix' => 'v1'], function () {

    // 扫码
    Route::group(['prefix' => 'scan', 'as' => 'scan.'], function () {
        // 用户码
        Route::post('user', 'UserController@scan')->name('user.code');

        // 订单码
        Route::post('order', 'OrderController@scan')->name('order.code');
    });

});