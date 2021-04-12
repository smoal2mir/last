<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $table = 'roles';
    protected $fillable = ['name', 'power', 'type', 'icon'];

    public function getIcon($size) {
        $icon = $this->icon ? '/uploads/roles/'.$this->icon : '/images/none.png';
        return '<img width="'.$size.'" height="'.$size.'" id="role_icon" src="'.$icon.'"/>';
    }

    public function isOwner() {
        return $this->type == "owner";
    }

    public static function getRoleName($id) {
        $role = self::find($id);
        if(!$role) return "/";
        return $role->name;
    }

    public static function getRoleByType($type) {
        $role = Role::where("type", $type)->first();
        return $role ? $role->id : 0;
    }

    /**
     * @return Array
     */
    public static function getRoleIds() {
        $roles = [];
        $roleObjects = Role::all();
        foreach($roleObjects as $role) {
            $roles[] = $role->id;
        }
        return $roles;
    }

    public static function isValid($role) {
        if(!$role) return false;
        $role = Role::find($role);
        return $role ? true : false;
    }

}
