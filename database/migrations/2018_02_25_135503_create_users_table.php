<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->string("name");
            $table->string("password");
            $table->string("avatar")->nullable();
            $table->string("decoration")->nullable();
            $table->string("gift")->nullable();
            $table->string("device");
            $table->string("ip");
            $table->string("country");
            $table->string("adminToken")->nullable();
            $table->string("ajax_token")->nullable();
            $table->string("status")->nullable();
            $table->string("statusColor")->default("#555555");
            $table->string("fontColor")->default("#000000");
            $table->string("nameColor")->default("#000000");
            $table->string("nameBgColor")->default("#FFFFFF00");
            $table->integer("fontSize")->default(24);
            $table->integer("reputation")->default(0);
            $table->boolean("is_virtual")->default(false);
            $table->integer("default_room")->default(1);
            $table->boolean("isPrivateDisabled")->default(false);
            $table->boolean("isRoleIconHidden")->default(false);
            $table->boolean("permanent_subscription")->default(false);
            $table->timestamp("subscription_start")->nullable();
            $table->timestamp("subscription_end")->nullable();
            $table->timestamp("last_login")->nullable();
            $table->string("upload_hash")->nullable();

            $table->text("orders_excpel")->nullable();
            $table->text("orders_ban")->nullable();
            $table->text("orders_notify")->nullable();
            $table->text("orders_gift")->nullable();
            $table->text("orders_remove_avatar")->nullable();
            $table->text("orders_change_decoartion")->nullable();
            $table->text("orders_excpel_from_room")->nullable();
            $table->text("room_create")->nullable();
            $table->text("room_edit")->nullable();
            $table->text("room_remove")->nullable();
            $table->text("room_max_pertinents")->nullable();
            $table->text("send_ad")->nullable();
            $table->text("wall_remove")->nullable();
            $table->text("wall_interval")->nullable();
            $table->text("public_msg_remove")->nullable();

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
        Schema::dropIfExists('users');
    }
}
