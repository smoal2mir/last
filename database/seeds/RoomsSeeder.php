<?php

use App\Room;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::create(["name" => "الغرفة العامة", "default" => true, "description" => "وصف الغرفة...ف1", "welcome" => "س١"]);
        Room::create(["name" => "روم الجناح الملكي", "default" => false, "description" => "وصف الغرفة..."]);
        Room::create(["name" => "روم الجزائر", "default" => false, "description" => "وصف الغرفة...", "welcome" => "و١"]);
        Room::create(["name" => "روم فلسطين", "default" => false, "description" => "وصف الغرفة...ف1"]);
        Room::create(["name" => "روم السعودية", "default" => false, "description" => "وصف الغرفة..."]);
        Room::create(["name" => "روم العراق", "default" => false, "description" => "وصف الغرفة...ف1", "welcome" => "س١"]);
    }
}
