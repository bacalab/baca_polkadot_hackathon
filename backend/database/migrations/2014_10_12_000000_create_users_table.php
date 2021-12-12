<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('device')->nullable()->comment('设备唯一标示');
            $table->string('unionid')->nullable()->comment('微信唯一标示');
            $table->string('invite_code')->comment('邀请码');
            $table->string('name')->comment('昵称');
            $table->string('headimgurl')->nullable()->comment('头像');
            $table->unsignedInteger('money')->default(0)->comment('余额 单位：分');
            $table->unsignedInteger('withdraw_limit')->default(0)->comment('余额 单位：分');
            $table->string('memo')->default("{}")->comment('备注');
            $table->rememberToken();
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
