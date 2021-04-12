<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ownerPrivilges = null;
        $roles = Role::all();
        foreach ($roles as $role) {
            if($role->isOwner()) {
                $ownerPrivilges = Permission::create(['role_id' => $role->id]);
            } else {
                Permission::create(['role_id' => $role->id]);
            }
        }
        if($ownerPrivilges) $ownerPrivilges->initForOwner();
    }
}
