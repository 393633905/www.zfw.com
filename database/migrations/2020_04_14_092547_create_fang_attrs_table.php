<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFangAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fang_attrs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0)->comment('上级ID');
            $table->string('field_name',50)->default('')->comment('字段名');
            $table->string('name',50)->default('')->comment('属性名称');
            $table->string('icon',200)->default('')->comment('图标');
            $table->timestamps();
            // 软删除
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fang_attrs');
    }
}
