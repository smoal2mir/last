<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banneds', function (Blueprint $table) {
            $table->increments('id');
			$table->string('username');
			$table->string('ip');
			$table->string('device');
			$table->string('country');
			$table->string('by');
			$table->text('cause')->nullable();
			$table->timestamp('finishes_at');
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
        Schema::dropIfExists('banneds');
    }
}
