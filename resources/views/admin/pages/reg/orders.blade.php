@extends('admin.master')

@section('content')
	<?php
	$actions = [
		"change-name" => "تغيير الإسم",
		"remove-avatar" => "حذف الصورة الشخصية",
		"remove-gift" => "حذف الهدية",
		"wall-post" => "نشر على الحائط",
		"wall-remove" => "خذف من الحائط",
		"notify" => "إرسال تنبيه",
		"excpel" => "طرد",
		"ban" => "حظر",
		"unban" => "رفع الحظر",
		"gift" => "إرسال هدية",
		"edit-privilege" => "تعديل صلاحية",
		"edit-role" => "تعديل سوبر",
		"orders-change-room" => "تغيير غرفة عضو"
	];
	?>
    <?php $activeLI = 13; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-orders-section">
            <div class="section2">
                <h3>سجل الأوامر</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.orders') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-orders-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-orders-search-form" style="display: {{$query ? "inline" : "none"}}; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم مستخدم أو أي كلمة">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-orders-delete">حذف</a></li>
                            <li><a href="#" id="reg-orders-empty">تغريغ سجل الأوامر</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $orders->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox" style="width: 17px; height: 17px;"></th>
                            <th>الإسم الأصلس للفاعل</th><th>الإسم المستعار للفاعل</th>
                            <th>الإسم الأصلي للعضو</th><th>الإسم المستعار للعضو</th>
                            <th>الوصف</th><th>العملية</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($orders as $order) {
                            if($order->actionType == 'remove-avatar') {
                                $note = '<a target="_blank" href="'.env("APP_HTTPS_HOST", env("APP_HTTP_HOST")).$order->notes.'">إضغط لمشاهدة المحتوى</a>';
                            } else {
                                $note = $order->notes;
                            }
                            echo '<tr row_id="' . $order->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $order->id . '"></td>';
                            echo '<td>' . $order->byRealName . '</td>';
                            echo '<td>' . $order->byNickName . '</td>';
                            echo '<td>' . $order->toNickName . '</td>';
                            echo '<td>' . $order->toNickName . '</td>';
                            echo '<td>' . $note . '</td>';
                            echo '<td><span class="orders-'.$order->actionType.'-action">' . $actions[$order->actionType] . '</span></td>';
                            echo '<td>' .$order->created_at. '</td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    @include("scripts.reg.orders")
@stop