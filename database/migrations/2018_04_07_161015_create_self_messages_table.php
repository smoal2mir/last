<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelfMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('self_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title");
            $table->text("msg");
            $table->integer("interval")->default(30);
            $table->enum("status", ["running", "paused"])->default("running");
            $table->string("lastToggle")->nullable();
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
        Schema::dropIfExists('self_messages');
    }
}
