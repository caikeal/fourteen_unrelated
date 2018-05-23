<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
