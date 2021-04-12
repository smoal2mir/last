<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
//		 $this->call(PermissionsSeeder::class); // only for production when changing chat from version 1 to 2
        $this->call(UsersSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(RoomsSeeder::class);
        $this->call(GiftsSeeder::class);
        $this->call(FacesSeeder::class);
        $this->call(ShortcutsSeeder::class);
    }
}
