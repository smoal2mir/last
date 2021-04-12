<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVirtualsToPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->boolean("admin_virtuals")->default(false)->after("admin_users_edit");
            $table->boolean("admin_virtuals_remove")->default(false)->after("admin_virtuals");
            $table->boolean("admin_virtuals_edit")->default(false)->after("admin_virtuals_remove");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(["admin_virtuals", "admin_virtuals_remove", "admin_virtuals_edit"]);
        });
    }
}
