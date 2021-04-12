<?php namespace App;


use Illuminate\Database\Eloquent\Model;

class OrderReg extends Model {

    protected $table = 'ordersReg';
    protected $fillable = ["byRealName", "byNickName", "toRealName", "toNickName", "notes", "actionType"];

    public static $eventTypes = [
        "change-name" => "تغيير الإسم",
        "remove-avatar" => "حذف الصورة الشخصية",
        "excpel" => "طرد",
        "ban" => "حظر",
        "unban" => "فك الحظر", // X
        "notify" => "تنبيه",
        "gift" => "إهداء",
        "remove-gift" => "سحب هدية",
        "wall-remove" => "حذف من الحائط",
        "orders-change-room" => "نقل العضو",
        "room-excpel" => "طرد من الغرفة",
        "edit-privilege" => "تعديل صلاحيه", // X
        "edit-role" => "تغيير رتبة",
    ];

    public static function getType($key) {
        if(!$key) return "/";
        return isset(self::$eventTypes[$key]) ? self::$eventTypes[$key] : "/";
    }

}