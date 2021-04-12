<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilteredWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filtered_words', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("word_id");
            $table->enum("word_type", ["denied", "controlled"]);
            $table->string("word");
            $table->string("full_text");
            $table->string("user");
            $table->string("ip");
            $table->timestamps();

            $table->foreign("word_id")->references("id")->on("words")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filtered_words');
    }
}
