<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = "settings";

    public static function parse() {
        return Settings::select([
            "chat_name", "chat_background", "background_color", "btn_color", "window_color", "content_color", "border_color", "show_chat_background",
            "show_banner", "banner_img", "show_chat_background_on_windows", "marquee_bg_color", "marquee_text_color", "marquee_content",
            "disable_marquee", "default_rooms_flag", "show_wall_banner", "wall_banner_msg", "wall_banner_background_color", "wall_banner_font_color",
            "self_messages_icon", "self_message_bg_color", "self_message_title_color", "self_message_content_color", "disable_floating_particles"
        ])->first();
    }

}
