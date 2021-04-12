<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('password')->nullable();
			$table->string('welcome')->nullable();
			$table->string('flag')->nullable();
			$table->string("description")->nullable();
			$table->integer('capacity')->default(50);
			$table->enum("target", ["all", "supers"])->default("all");
			$table->boolean('default')->default(false);
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
        Schema::dropIfExists('rooms');
    }
}
