<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create([
        	"role_id" => 1,
        	"name" => "Youcam39",
			"password" => bcrypt("f6dgjd8dg8aaagkl"),
			"country" => "DZ",
			"ip" => "105.107.141.60",
			"device" => "Mac-OS.10.12.6.Chrome.15z7myr.1lfszkt.15b5j0c.1rwg0pz.1vg1lwt.1s4h4pt"
		]);

        User::create([
            "role_id" => 1,
            "name" => "إدارة",
            "password" => bcrypt("f6dgjd8dg8aaagkl"),
            "country" => "DZ",
            "ip" => "105.107.141.60",
            "device" => "Mac-OS.10.12.6.Chrome.15z7myr.1lfszkt.15b5j0c.1rwg0pz.1vg1lwt.1s4h4pt"
        ]);

    }
}

