<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWallCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wall_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->unsignedInteger("post_id");
            $table->string("comment");
            $table->unsignedInteger("likes")->default(0);
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->oonDelete("cascade");
            $table->foreign("post_id")->references("id")->on("wall")->oonDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wall_comments');
    }
}
