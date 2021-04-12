<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    protected $table = 'users';
    protected $fillable = ['role_id', 'name', 'password', 'country', 'device', 'ip', 'is_virtual', 'default_room', 'has_ext_admin'];
    protected $dates    = ['last_login', 'subscription_start', 'subscription_end'];

	public function hasRegistryAccess() {
		return $this->hasPermission("admin_reg");
	}

	public function hasRegistryBannedsAccess() {
		return $this->hasPermission("admin_reg");
	}

	public function hasRegistryExcpelAccess() {
		return $this->hasPermission("admin_reg");
	}

	public function canManageSelfMessages() {
		return $this->hasPermission("admin_settings");
	}

	public function canRevealNicknames() {
		return $this->hasPermission("reveal_names");
	}

	public function hasFrontAdminAccess() {
		return $this->hasPermission("admin");
	}

	public function isGreaterThan($roleID) {
		try {
			$role = Role::find($this->role_id);
			if(!$role) return false;
			if($role->type == "owner") return true;
			$targetRole = Role::find($roleID);
			return $role->power .' | '. $targetRole->power.' | '.$roleID.' | ';
		} catch(\Exception $e) {
			return true;
		}
	}

	public function hasPermission($permissionKey) {
		try {
			$role = Role::find($this->role_id);
			if(!$role) return false;

			if($this->role_id == $role->getRoleByType("owner")) return true;

			$permission = Permission::where("role_id", $role->id)->first();

			return $permission[$permissionKey] > 0;

		} catch(\Exception $e){
			return false;
		}
	}

	public function getRoleToUpgrade($withoutBasic = false) {
		try {
			$roles = [];
			$permission = Permission::where("role_id", $this->role_id)->first();
			if(!$permission) return [];
			$permissionRoles = explode(",", $permission->orders_upgrade_roles);

			foreach($permissionRoles as $role) {
				$role = trim($role);
				if(strlen($role)) {
					if($withoutBasic && Role::getRoleByType("basic") == $role) continue;
					$roles[] = $role;
				}
			}

			return Role::whereIn("id", $roles)->orderBy("power", "DESC")->get();
		} catch(\Exception $e) {
			return [];
		}
	}

    public function subscriptionEnd() {
        $date = '/';
        $class = '';
        if($this->isOwner() || $this->permanent_subscription)  {
            $date = 'دائم';
            $class = "green";
        } else {
            $date = new Carbon($this->subscription_end);

            $diff = $date->diffInDays(new Carbon());
            if($diff > 1) $class = "red";
            if($diff >= 2) $class = "orange";
            if($diff > 7) $class = "green";

            $date = $date->format("Y:m:d");
        }

        return "<span class='finishes ".$class."'>$date</span>";
    }

    public function checkSubscriptionExpiry() {
        $date = new Carbon($this->subscription_end);
        if(($date->isPast() || is_null($this->subscription_end)) && !$this->isOwner() && !$this->permanent_subscription) {
            $this->role_id = Role::getRoleByType("basic");
            $this->subscription_end = null;
            $this->save();
        }
    }

	public function isOwner() {
		return Role::getRoleByType("owner") == $this->role_id;
	}

	public function isBasic() {
		return Role::getRoleByType("basic") == $this->role_id;
	}

}
