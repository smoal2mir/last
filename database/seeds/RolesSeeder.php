<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Role::truncate();
        Permission::truncate();

        $role = Role::create(['name' => 'صاحب الموقع', 'power' => 100000, 'type' => 'owner', 'icon' => "91_7f62l7b.png"]);
        $ownerPrivilges = Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'أدمن', 'power' => 500, 'icon' => "b1udz7w1m2.gif"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'درع ذهبي', 'power' => 30, 'icon' => "izr4s0kgnc.png"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'مراقب ذهبي', 'power' => 29, 'icon' => "d9_w4zn8oh.png"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'مراقب وردي', 'power' => 28, 'icon' => "9qcuor693j.gif"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'مراقب فضي', 'power' => 27, 'icon' => "lcd4hjd4os.gif"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'مراقب أسود', 'power' => 26, 'icon' => "mped1t2tki.png"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'مراقب أحمر', 'power' => 25, 'icon' => "i5_38z0tqb.png"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'مراقب أزرق', 'power' => 24, 'icon' => "jpr5ekftgf.png"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'VIP مراقب', 'power' => 23, 'icon' => "41ekfi2ayi.png"]);
        Permission::create(['role_id' => $role->id]);

        $role = Role::create(['name' => 'عضو', 'power' => 1, 'type' => 'basic']);
        Permission::create(['role_id' => $role->id]);

		$role = Role::create(['name' => 'زائر', 'power' => 0, 'type' => 'guest']);
		Permission::create(['role_id' => $role->id]);

        $ownerPrivilges->initForOwner();
    }
}
