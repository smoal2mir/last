<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    protected $table = 'permissions';
    protected $fillable = ["role_id"];

    public function initForOwner() {
        $this->orders_excpel = "10000|hour";
        $this->orders_ban = "10000|hour";
        $this->orders_notify = "10000|hour";
        $this->orders_gift = "10000|hour";
        $this->orders_remove_avatar = "10000|hour";
        $this->orders_change_decoartion = "10000|hour";
        $this->orders_excpel_from_room = "10000|hour";
        $this->room_create = "10000|hour";
        $this->room_edit = "10000|hour";
        $this->room_remove = "10000|hour";
        $this->room_max_pertinents = "10000|hour";
        $this->send_ad = "10000|hour";
        $this->wall_remove = "10000|hour";
        $this->wall_interval = "10000|hour";
        $this->wall_likes = 1;
        $this->private_likes = 1;
        $this->album_length = 100;
        $this->public_msg_remove = "10000|hour";

        $this->orders_upgrade = 1;
        $this->hidden = 1;
        $this->edit_permissions = 1;
        $this->reveal_names = 1;
        $this->open_private = 1;
        $this->like_controls = 1;
        $this->open_full_rooms = 1;
        $this->open_locked_rooms = 1;
        $this->admin = 1;
        $this->admin_users = 1;
        $this->admin_users_remove = 1;
        $this->admin_users_edit = 1;
        $this->admin_virtuals = 1;
        $this->admin_virtuals_remove = 1;
        $this->admin_virtuals_edit = 1;
        $this->admin_banneds = 1;
        $this->admin_banneds_remove = 1;
        $this->admin_rooms = 1;
        $this->admin_rooms_edit = 1;
        $this->admin_rooms_remove = 1;
        $this->admin_supers = 1;
        $this->admin_supers_remove = 1;
        $this->admin_settings = 1;

        $this->orders_upgrade_roles = implode(",", Role::getRoleIds());
        $this->can_see_hidden = "";

        $this->save();
    }

    public static function maxAlbumPhotos($role_id) {
        $permissions = Permission::where("role_id", $role_id)->first();
        return $permissions ? $permissions->album_length : 0;
    }

}
