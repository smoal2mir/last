<?php

Route::get('/resize-avatars', function() {

    $smallPath   = public_path() . '/uploads/avatars/sm/';
    $largePath   = public_path() . '/uploads/avatars/lg/';

    if(!File::exists($smallPath))
        File::makeDirectory($smallPath, 0775, true);

    if(!File::exists($largePath))
        File::makeDirectory($largePath, 0775, true);

    foreach (glob(public_path() . '/uploads/avatars/*') as $item) {
        try {

            if(\Illuminate\Support\Facades\File::isFile($item)) {
                $file = \Illuminate\Support\Facades\File::get($item);
                $baseName = \Illuminate\Support\Facades\File::basename($item);

                Image::make($file)
                    ->resize(null, 35, function($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save($smallPath.$baseName, 95);

                Image::make($file)
                    ->resize(null, 200, function($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save($largePath.$baseName, 95);

                if($baseName != "default.png" && $baseName != settings("default_avatar"))
                    unlink(public_path() ."/uploads/avatars/".$baseName);

            }

        } catch(\Exception $e) {

        }
    }

    echo "done";

});

Route::get('/', 'HomeController@index');
Route::post('/auth/guest-login', 'AuthController@guestLogin');
Route::post('/auth/member-login', 'AuthController@memberLogin');
Route::post('/auth/change-password', 'AuthController@changePassword');
Route::post('/auth/register', 'AuthController@register');
Route::post('/change_avatar', 'GuestController@changeAvatar');
Route::post('/send_private_media', 'UploadController@sendPrivateMedia');
Route::post('/album_add_photo', 'UploadController@addPhotoToAlbum');
Route::post('/wall_upload_photo_post', 'WallController@uploadPhotoPost');
Route::post('/wall_upload_sound_post', 'WallController@uploadSoundPost');
Route::post('/wall_upload_video_post', 'WallController@uploadVideoPost');

/**
 * Static Pages
 */
Route::get('contact', 'StaticPagesController@getContact');
Route::get('esh', 'StaticPagesController@getEsh');
Route::get('rules', 'StaticPagesController@getRules');

/**
 * Front Administratino Area
 */
/* Home Page Related Routes */
Route::get('/fadmn/home/{token}', 'FadminController@getHome');

/* Reg Related Routes */
Route::get('/fadmn/reg/{token}', 'FadminController@getReg');
Route::post('/fadmn/reg/{token}', 'FadminController@postReg');

/* Events Related Routes */
Route::get('/fadmn/events/{token}', 'FadminController@getEvents');
Route::post('/fadmn/events/{token}', 'FadminController@postEvents');

/* Users Related Routes */
Route::get('/fadmn/users/{token}', 'FadminController@getUsers');
Route::post('/fadmn/users/{token}/add', 'FadminController@postAddUser');
Route::post('/fadmn/users/{token}/update-role', 'FadminController@postUpgradeUsersRole');
Route::post('/fadmn/users/{token}/delete', 'FadminController@postDeleteUser');
Route::post('/fadmn/users/{token}/change-password', 'FadminController@postChangeUserPassword');

/* Virtual Users Related Routes */
Route::get('/fadmn/virtuals/{token}', 'FadminController@getVirtualUsers');
Route::post('/fadmn/virtuals/{token}/add', 'FadminController@postAddVirtualUser');
Route::post('/fadmn/virtuals/{token}/edit', 'FadminController@postEditVirtualUsers');
Route::post('/fadmn/virtuals/{token}/delete', 'FadminController@postDeleteVirtualUser');
Route::post('/fadmn/virtuals/{token}/change-avatar', 'FadminController@postChangeVirtualUserAvatar');
Route::post('/fadmn/virtuals/{token}/settings', 'FadminController@postVirtualUsersSettings');

/* Rooms Related Routes */
Route::get('/fadmn/rooms/{token}', 'FadminController@getRooms');
Route::post('/fadmn/rooms/{token}/add', 'FadminController@postAddRoom');
Route::post('/fadmn/rooms/{token}/delete', 'FadminController@postDeleteRooms');
Route::post('/fadmn/rooms/{token}/edit', 'FadminController@postEditRooms');
Route::post('/fadmn/rooms/{token}/edit-flag', 'FadminController@postChangeRoomFlag');
Route::post('/fadmn/rooms/{token}/change-default-room-flag', 'FadminController@postSettingsChangeDefaultRoomFlag');

/* Banneds Related Routes */
Route::get('/fadmn/ban/{token}', 'FadminController@getBan');
Route::post('/fadmn/ban/{token}', 'FadminController@postBan');
Route::post('/fadmn/ban-device-or-ip/{token}', 'FadminController@postBanDeviceOrIp');

/* Permissions Related Routes */
Route::get('/fadmn/privileges/{token}', 'FadminController@getPermissions');
Route::post('/fadmn/privileges/{token}', 'FadminController@postPermissions');
Route::post('/fadmn/privileges/{token}/save', 'FadminController@postSavePermissions');
Route::post('/fadmn/privileges/{token}/icon', 'FadminController@postPermissionsSaveIcon');
Route::post('/fadmn/privileges/{token}/remove-icon', 'FadminController@postPermissionsRemoveIcon');

/* Words Filter Related Routes */
Route::get('/fadmn/filter/{token}', 'FadminController@getFilter');
Route::post('/fadmn/filter/{token}/add', 'FadminController@postFilterAddWord');
Route::post('/fadmn/filter/{token}/remove', 'FadminController@postFilterRemoveWord');
Route::post('/fadmn/filter/{token}/edit', 'FadminController@postFilterEditWord');

/* Filtered Words Related Routes */
Route::get('/fadmn/filtered-words/{token}', 'FadminController@getFilteredWords');
Route::post('/fadmn/filtered-words/{token}/edit', 'FadminController@postFilteredWordEdit');
Route::post('/fadmn/filtered-words/{token}/clear', 'FadminController@postFilteredWordClear');
Route::post('/fadmn/filtered-words/{token}/remove', 'FadminController@postFilteredWordRemove');

/* Subscriptions Related Routes */
Route::get('/fadmn/subscriptions/{token}', 'FadminController@getSubscriptions');
Route::post('/fadmn/subscriptions/{token}/update-role', 'FadminController@postUpgradeUsersRole');
Route::post('/fadmn/subscriptions/{token}/update-subscription', 'FadminController@postUpdateSubscription');
Route::post('/fadmn/subscriptions/{token}/update-subscription-permanent', 'FadminController@postUpdateSubscriptionPermanent');
Route::post('/fadmn/subscriptions/{token}/delete', 'FadminController@postDeleteUser');

/* Gifts and Faces Related Routes */
Route::get('/fadmn/gifts-and-faces/{token}', 'FadminController@getGiftsAndFaces');
Route::post('/fadmn/gifts-and-faces/{token}/add', 'FadminController@postGiftsFacesSaveIcon');
Route::post('/fadmn/gifts-and-faces/{token}/remove', 'FadminController@postGiftsFacesRemoveIcon');
Route::post('/fadmn/gifts-and-faces/{token}/update-face-key', 'FadminController@postUpdateFaceKey');

/* Button Background Related Routes */
Route::get('/fadmn/btn-bgs/{token}', 'FadminController@getBtnBgs');
Route::post('/fadmn/btn-bgs/{token}/add', 'FadminController@postBtnBgsSaveIcon');
Route::post('/fadmn/btn-bgs/{token}/remove', 'FadminController@postBtnBgsRemoveIcon');

/* Shortcuts Related Routes */
Route::get('/fadmn/shortcuts/{token}', 'FadminController@getShortcuts');
Route::post('/fadmn/shortcuts/{token}/add', 'FadminController@postAddShortcut');
Route::post('/fadmn/shortcuts/{token}/edit', 'FadminController@postEditShortcut');
Route::post('/fadmn/shortcuts/{token}/remove', 'FadminController@postRemoveShortcut');

/* Self Messages Related Routes */
Route::get('/fadmn/self-messages/{token}', 'FadminController@getSelfMessages');
Route::post('/fadmn/self-messages/{token}/add', 'FadminController@postAddSelfMessage');
Route::post('/fadmn/self-messages/{token}/edit', 'FadminController@postEditSelfMessage');
Route::post('/fadmn/self-messages/{token}/remove', 'FadminController@postRemoveSelfMessage');
Route::post('/fadmn/self-messages/{token}/change-icon', 'FadminController@postSettingsChangeSelfMessagesIcon');
Route::post('/fadmn/self-messages/{token}/self-messages-colors', 'FadminController@seflMessagesColors');

/* Settings Related Routes */
Route::get('/fadmn/settings/{token}', 'FadminController@getSettings');
Route::post('/fadmn/settings/{token}/seoSettings/', 'FadminController@posSettingsSEO');
Route::post('/fadmn/settings/{token}/clear-wall', 'FadminController@posSettingsClearWall');
Route::post('/fadmn/settings/{token}/change-default-avatar', 'FadminController@postSettingsChangeDefaultAvatar');
Route::post('/fadmn/settings/{token}/change-banner', 'FadminController@postSettingsChangeBanner');
Route::post('/fadmn/settings/{token}/change-interface-background', 'FadminController@postSettingsChangeInterfaceBackground');
Route::post('/fadmn/settings/{token}/change-chat-background', 'FadminController@postSettingsChangeChatBackground');
Route::post('/fadmn/settings/{token}/change-btn-bg', 'FadminController@postSettingsChangeBtnBg');
Route::post('/fadmn/settings/{token}/remove-btn-bg', 'FadminController@postSettingsRemoveBtnBg');
Route::post('/fadmn/settings/{token}/wallSettings', 'FadminController@posSettingsWall');
Route::post('/fadmn/settings/{token}/interfaceSettings', 'FadminController@posInterfaceettings');
Route::post('/fadmn/settings/{token}/marqueeSettings', 'FadminController@posMarqueSttings');
Route::post('/fadmn/settings/{token}/postVirtualSettings', 'FadminController@posSettingsVirtuals');
Route::post('/fadmn/settings/{token}/restart', 'FadminController@posSettingsRestartChat');
Route::post('/fadmn/settings/{token}/general', 'FadminController@posSettingsGeneral');
Route::get('/fadmn/developer/{token}', 'FadminController@getDeveloperSettings');
Route::post('/fadmn/developer/{token}/developerSettings/', 'FadminController@postDeveloperSettings');


/**
 * Back Admin Area
 */
Route::namespace('Admin')->group(function () {

    /**
     * Users Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin', ['as' => 'login', 'uses' => 'UsersController@getLogin', 'middleware' => 'guest']);
    Route::get(env('ADMIN_HASH').'/admin/logout', ['as' => 'logout', 'uses' => 'UsersController@getLogout']);
    Route::post(env('ADMIN_HASH').'/admin', ['uses' => 'UsersController@postLogin']);
    Route::get(env('ADMIN_HASH').'/admin/users', ['as' => 'users', 'uses' => 'UsersController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/users/add', ['as' => 'users.add', 'uses' => 'UsersController@postAdd']);
    Route::post(env('ADMIN_HASH').'/admin/users/search', ['as' => 'users.search', 'uses' => 'UsersController@postSearch']);
    Route::post(env('ADMIN_HASH').'/admin/users/delete', 'UsersController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/users/ban', 'UsersController@postBan');
    Route::post(env('ADMIN_HASH').'/admin/users/save', 'UsersController@postSave');

    /**
     * Banneds Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/banneds', ['as' => 'banneds', 'uses' => 'BannedsController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/banneds/add', ['as' => 'banneds.add', 'uses' => 'BannedsController@postAdd']);
    Route::post(env('ADMIN_HASH').'/admin/banneds/search', ['as' => 'banneds.search', 'uses' => 'BannedsController@postSearch']);
    Route::post(env('ADMIN_HASH').'/admin/banneds/unban', 'BannedsController@postUnban');
    Route::post(env('ADMIN_HASH').'/admin/banneds/change', 'BannedsController@postChange');

    /**
     * Rooms Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/rooms', ['as' => 'rooms', 'uses' => 'RoomsController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/rooms/add', ['as' => 'rooms.add', 'uses' => 'RoomsController@postAdd']);
    Route::post(env('ADMIN_HASH').'/admin/rooms/search', ['as' => 'rooms.search', 'uses' => 'RoomsController@postSearch']);
    Route::post(env('ADMIN_HASH').'/admin/rooms/delete', 'RoomsController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/rooms/save', 'RoomsController@postSave');
    Route::post('rooms/all', 'RoomsController@postAll');

    /**
     * Settings Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/settings', ['as' => 'settings', 'uses' => 'SettingsController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/settingsCredintials', 'SettingsController@postCredintials');
    Route::post(env('ADMIN_HASH').'/admin/settings', 'SettingsController@posSettings');
    Route::post(env('ADMIN_HASH').'/admin/getSettings', 'SettingsController@posGetSettings');
    Route::post(env('ADMIN_HASH').'/admin/postVirtualSettings', 'SettingsController@postVirtualSettings');
    Route::post(env('ADMIN_HASH').'/admin/wallSettings', 'SettingsController@posWallSettings');
    Route::post(env('ADMIN_HASH').'/admin/clearWall', 'WallController@postEmpty');
    Route::post(env('ADMIN_HASH').'/admin/start-chat', 'SettingsController@postStartChat');
    Route::post(env('ADMIN_HASH').'/admin/restart-chat', 'SettingsController@postRestartChat');
    Route::post(env('ADMIN_HASH').'/admin/stop-chat', 'SettingsController@postStopChat');


    /**
     * Private Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-private', ['as' => 'reg.private', 'uses' => 'RegPrivateController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-private/search', 'RegPrivateController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-private/delete', 'RegPrivateController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-private/empty', 'RegPrivateController@postEmpty');
    Route::post(env('ADMIN_HASH').'/admin/reg-private/empty-attach', 'RegPrivateController@postEmptyAttachments');

    /**
     * Public Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-public', ['as' => 'reg.public', 'uses' => 'RegPublicController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-public/search', 'RegPublicController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-public/delete', 'RegPublicController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-public/empty', 'RegPublicController@postEmpty');

    /**
     * Wall Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-wall', ['as' => 'reg.wall', 'uses' => 'RegWallController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-wall/search', 'RegWallController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-wall/delete', 'RegWallController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-wall/empty', 'RegWallController@postEmpty');
    Route::post(env('ADMIN_HASH').'/admin/reg-wall/empty-attach', 'RegWallController@postEmptyAttachments');

    /**
     * Notifications Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-notifications', ['as' => 'reg.notifications', 'uses' => 'RegNotificationController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-notifications/search', 'RegNotificationController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-notifications/delete', 'RegNotificationController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-notifications/empty', 'RegNotificationController@postEmpty');

    /**
     * Ads Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-ads', ['as' => 'reg.ads', 'uses' => 'RegAdController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-ads/search', 'RegAdController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-ads/delete', 'RegAdController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-ads/empty', 'RegAdController@postEmpty');

    /**
     * Bans Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-bans', ['as' => 'reg.bans', 'uses' => 'RegBanController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-bans/search', 'RegBanController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-bans/delete', 'RegBanController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-bans/empty', 'RegBanController@postEmpty');

    /**
     * Excpels Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-excpels', ['as' => 'reg.excpels', 'uses' => 'RegExcpelController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-excpels/search', 'RegExcpelController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-excpels/delete', 'RegExcpelController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-excpels/empty', 'RegExcpelController@postEmpty');

    /**
     * Rooms Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-rooms', ['as' => 'reg.rooms', 'uses' => 'RegRoomController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-rooms/search', 'RegRoomController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-rooms/delete', 'RegRoomController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-rooms/empty', 'RegRoomController@postEmpty');


    /**
     * Orders Registry Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/reg-orders', ['as' => 'reg.orders', 'uses' => 'RegOrderController@index', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reg-orders/search', 'RegOrderController@postSearch');
    Route::post(env('ADMIN_HASH').'/admin/reg-orders/delete', 'RegOrderController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reg-orders/empty', 'RegOrderController@postEmpty');


    /**
     * Self Messages Administration Area
     */
    Route::get(env('ADMIN_HASH').'/admin/self-messages', ['as' => 'self-messages', 'uses' => 'SelfMessageController@getAll', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/self-messages/add', ['as' => 'self-messages.add', 'uses' => 'SelfMessageController@postAdd']);
    Route::post(env('ADMIN_HASH').'/admin/self-messages/search', ['as' => 'self-messages.search', 'uses' => 'SelfMessageController@postSearch']);
    Route::post(env('ADMIN_HASH').'/admin/self-messages/delete', 'SelfMessageController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/self-messages/save', 'SelfMessageController@postSave');
    Route::post(env('ADMIN_HASH').'/admin/self-messages/all', 'SelfMessageController@postAll');


    /**
     * Reveal nicknames
     */
    Route::get(env('ADMIN_HASH').'/admin/reveal-nicknames', ['as' => 'reveal-nicknames', 'uses' => 'RevealNicknamesController@getAll', 'middleware' => 'auth']);
    Route::post(env('ADMIN_HASH').'/admin/reveal-nicknames/search', ['as' => 'reveal-nicknames.search', 'uses' => 'RevealNicknamesController@postSearch']);
    Route::post(env('ADMIN_HASH').'/admin/reveal-nicknames/delete', 'RevealNicknamesController@postDelete');
    Route::post(env('ADMIN_HASH').'/admin/reveal-nicknames/empty', 'RevealNicknamesController@postEmpty');

});
