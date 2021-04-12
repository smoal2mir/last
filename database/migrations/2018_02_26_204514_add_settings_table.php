<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');

			// general settings
			$table->integer('required_likes_wall')->default(50);
			$table->integer('required_likes_notify')->default(50);
			$table->integer('required_likes_private')->default(50);
			$table->integer('public_msg_length')->default(500);
			$table->integer('private_msg_length')->default(300);
			$table->boolean('allow_multi_sessions')->default(true);
			$table->integer('time_between_messages')->default(3);
			$table->boolean('disable_guest_private_messages')->default(false);
			$table->boolean('disable_private_messages')->default(false);
			$table->boolean('disable_foreing_contries')->default(false);
			$table->boolean('disable_guest_login')->default(false);
			$table->boolean('disable_signup')->default(false);
			$table->string('default_avatar')->nullable();
			$table->string('default_rooms_flag')->nullable();
			$table->text('bolcked_countries')->nullable();

			// SEO settings
			$table->string('chat_name')->nullable();
			$table->string('chat_title')->nullable();
			$table->text('chat_desc')->nullable();
			$table->text('chat_keywords')->nullable();

			// desing settings
			$table->string('background_color')->default("#FFFFFF");
			$table->string('btn_color')->default("#d398c1");
			$table->string('window_color')->default("#c765c1");
			$table->string('content_color')->default("#708090");
			$table->boolean('show_banner')->default(true);
			$table->string('banner_img')->nullable();
			$table->boolean('show_chat_background')->default(true);
			$table->string('chat_background')->nullable();
			$table->boolean('show_chat_background_on_windows')->default(true);

            // marquee settings
            $table->string('marquee_bg_color')->default("#c765c1");
            $table->string('marquee_text_color')->default("#FFFFFF");
            $table->text("marquee_content")->nullable();
            $table->boolean("disable_marquee")->default(false);

			// virtuals settings
			$table->integer('virtuals_count')->default(15);
			$table->boolean('disable_virtuals')->default(false);

			// wall settings
			$table->integer('wall_msg_count')->default(35);
			$table->boolean('show_wall_banner')->default(true);
			$table->text('wall_banner_msg')->nullable();
			$table->string('wall_banner_background_color')->default("#faf0e6");
			$table->string('wall_banner_font_color')->default("#a72a2a");

			// self messages settings
            $table->string('self_messages_icon')->nullable();
			$table->string('self_message_bg_color')->default("#d7f5f7");
			$table->string('self_message_title_color')->default("#7770a7");
			$table->string('self_message_content_color')->default("#a72a2a");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
