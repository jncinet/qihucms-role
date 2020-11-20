<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 签约项目
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('签约名称');
            $table->string('slug')->comment('标识');
            $table->text('desc')->nullable()->comment('简介');
            $table->unsignedMediumInteger('times')->default(0)->comment('有效时长');
            $table->enum('unit', ['days', 'weeks', 'months', 'year'])
                ->default('days')->comment('单位');
            $table->boolean('is_qualification_pa')->default(0)->comment('是否需要完成个人认证');
            $table->boolean('is_qualification_co')->default(0)->comment('是否需要完成企业认证');
            $table->unsignedBigInteger('currency_type_id')->default(0)->comment('货币类型');
            $table->unsignedDecimal('price')->default(0)->comment('价格');
            $table->timestamps();
        });

        // 功能权限
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('功能名称');
            $table->string('slug')->nullable()->comment('标识');
            $table->unsignedMediumInteger('amount')->default(0)->comment('数量限制');
            $table->timestamps();
        });

        // 签约项目后拥有功能权限
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('permission_id')->index()->comment('功能权限ID');
            $table->unsignedBigInteger('role_id')->index()->comment('签约项目ID');
            $table->timestamps();
        });

        // 会员己经签约的项目
        Schema::create('role_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('会员ID');
            $table->unsignedBigInteger('role_id')->index()->comment('签约项目ID');
            $table->timestamp('expires')->nullable()->comment('到期时间');
            $table->timestamps();
        });

        // 会员已经拥有的功能权限
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index()->comment('会员ID');
            $table->unsignedBigInteger('permission_id')->index()->comment('功能权限ID');
            $table->timestamp('expires')->nullable()->comment('到期时间');
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
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('user_permissions');
    }
}
