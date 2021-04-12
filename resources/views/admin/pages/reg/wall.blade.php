@extends('admin.master')

@section('content')
    <?php
    $types = ["text" => "رسالة نصية", "photo" => "صورة", "sound" => "مقطع صوتي", "video" => "مقطع مرئي", "youtube" => "يوتيوب"];
    $actions = ["add" => "نشر", "remove" => "حذف"];
    ?>
    <?php $activeLI = 7; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-wall-section">
            <div class="section2">
                <h3>سجل الحائط</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.wall') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-wall-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-wall-search-form" style="display: {{$query ? "inline" : "none"}}; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم مستخدم أو أي كلمة">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-wall-delete">حذف</a></li>
                            <li><a href="#" id="reg-wall-empty">تفريغ سجل الحائط</a></li>
                            <li><a href="#" id="reg-wall-empty-attach">حذف جميع المرفقات</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $posts->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox" style="width: 17px; height: 17px;"></th>
                            <th>الإسم الأصلي للمرسل</th><th>الإسم المستعار للمرسل</th>
                            <th>الرسالة</th><th>نوع الرسالة</th><th>العملية</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($posts as $post) {
                            if($post->msgType != 'text') {
                                if($post->msgType == 'youtube') {
                                    $text = '<a target="_blank" href="https://www.youtube.com/watch?v='.$post->msg.'">إضغط لمشاهدة المحتوى</a>';
                                } else {
                                    $text = '<a target="_blank" href="'.env("APP_URL").'uploads/wall/'.$post->msgType.'s/'.$post->msg.'">إضغط لمشاهدة المحتوى</a>';
                                }
                            } else {
                                $text = $post->msg;
                            }
                            echo '<tr row_id="' . $post->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $post->id . '"></td>';
                            echo '<td>' . $post->byRealName . '</td>';
                            echo '<td>' . $post->byNickName . '</td>';
                            echo '<td>' . $text . '</td>';
                            echo '<td>' . $types[$post->msgType] . '</td>';
                            echo '<td><span class="wall-'.$post->actionType.'-action">' . $actions[$post->actionType] . '</span></td>';
                            echo '<td>' .$post->created_at. '</td></tr>';
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
    @include("scripts.reg.wall")
@stop