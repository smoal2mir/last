<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBansRegTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bansReg', function (Blueprint $table) {
            $table->increments('id');
            $table->string("byRealName");
            $table->string("byNickName");
            $table->string("toRealName");
            $table->string("toNickName");
            $table->string("reason")->nullable();
            $table->enum("actionType", ["ban", "unban"]);
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
        Schema::dropIfExists('bansReg');
    }
}
