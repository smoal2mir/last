<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsRegTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificationsReg', function (Blueprint $table) {
            $table->increments('id');
            $table->string("byRealName");
            $table->string("byNickName");
            $table->string("toRealName");
            $table->string("toNickName");
            $table->string("msg");
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
        Schema::dropIfExists('notificationsReg');
    }
}
