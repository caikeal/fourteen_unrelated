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

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->comment('会员号');
            $table->string('name')->nullable()->comment('真实姓名');
            $table->string('open_id')->nullable()->comment('微信id');
            $table->string('wx_name')->nullable()->comment('微信名');
            $table->text('wx_avatar')->nullable()->comment('微信头像地址');
            $table->string('mobile')->nullable()->comment('手机号');
            $table->string('password')->nullable()->comment('密码');
            $table->string('no_qrcode')->comment('会员二维码地址');
            $table->string('from_store_id')->default(0)->comment('来源门店 ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
