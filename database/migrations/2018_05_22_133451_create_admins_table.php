<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id')->index()->comment('关联门店ID');
            $table->string('mobile')->comment('手机号');
            $table->string('password')->comment('密码');
            $table->string('name')->comment('姓名');
            $table->boolean('is_admin')->default(0)->comment('管理员');
            $table->boolean('is_store_manager')->default(0)->comment('店长');
            $table->boolean('is_staff')->default(0)->comment('员工');
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
        Schema::dropIfExists('admins');
    }
}
