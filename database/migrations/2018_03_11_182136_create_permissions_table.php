<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');

			$table->integer("role_id")->unsigned();

			$table->string("orders_excpel")->default("0|hour");
			$table->string("orders_ban")->default("0|hour");
			$table->string("orders_notify")->default("0|hour");
			$table->string("orders_gift")->default("0|hour");
			$table->string("orders_remove_avatar")->default("0|hour");
			$table->string("orders_change_decoartion")->default("0|hour");
			$table->string("orders_excpel_from_room")->default("0|hour");
			$table->boolean("orders_upgrade")->default(false);
			$table->text("orders_upgrade_roles")->nullable();

			$table->string("room_create")->default("0|hour");
			$table->string("room_edit")->default("0|hour");
			$table->string("room_remove")->default("0|hour");
			$table->string("room_max_pertinents")->default("0|hour");

			$table->string("send_ad")->default("0|hour");
			$table->string("wall_remove")->default("0|hour");
			$table->string("wall_interval")->default("0|minute");
			$table->integer("wall_likes")->default(0);
			$table->integer("private_likes")->default(0);
            $table->unsignedInteger("album_length")->default(0);
			$table->boolean("hidden")->default(false);
			$table->text("can_see_hidden")->nullable();
			$table->boolean("edit_permissions")->default(false);
			$table->boolean("reveal_names")->default(false);
			$table->boolean("open_private")->default(false);
			$table->boolean("open_full_rooms")->default(false);
			$table->boolean("open_locked_rooms")->default(false);
			$table->string("public_msg_remove")->default("0|hour");
			$table->boolean("orders_change_room")->default(false);
			$table->boolean("show_real_name")->default(false);
			$table->boolean("change_btns_bg")->default(false);
			$table->boolean("show_ip")->default(false);
			$table->boolean("like_controls")->default(false);
			$table->boolean("wall_banner")->default(false);

			$table->boolean("admin")->default(false);
			$table->boolean("admin_events")->default(false);
			$table->boolean("admin_reg")->default(false);
			$table->boolean("admin_users")->default(false);
			$table->boolean("admin_users_remove")->default(false);
			$table->boolean("admin_users_edit")->default(false);
			$table->boolean("admin_banneds")->default(false);
			$table->boolean("admin_banneds_remove")->default(false);
			$table->boolean("admin_rooms")->default(false);
			$table->boolean("admin_rooms_edit")->default(false);
			$table->boolean("admin_rooms_remove")->default(false);
			$table->boolean("admin_supers")->default(false);
			$table->boolean("admin_supers_remove")->default(false);
			$table->boolean("admin_settings")->default(false);

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
        Schema::dropIfExists('permissions');
    }
}
