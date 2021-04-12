<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevealNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reveal_names', function (Blueprint $table) {
			$table->increments('id');
			$table->string("username");
			$table->string("role")->nullable();
			$table->string("ip");
			$table->string("device")->nullable();
			$table->string("decoration")->nullable();
			$table->string("country")->nullable();
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
        Schema::dropIfExists('reveal_names');
    }
}
