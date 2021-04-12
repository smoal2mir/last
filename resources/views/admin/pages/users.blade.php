@extends('admin.master')

@section('content')
    <?php $activeLI = 1; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="users-section">
            <div class="section1">
                <h3>تسجيل مستخدم جديد</h3>

                @if(Session::has("errors"))
                    <div class="alert alert-danger">{{$errors->first()}}</div>
                @endif

                @if(Session::has("success_msg"))
                    <div class="alert alert-success">{{Session::get("success_msg")}}</div>
                @endif

                <form action="{{ route('users.add') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="username" style="color: #777; font-weight: normal;">إسم المستخدم</label>
                        <input type="text" name="username" id="username" placeholder="إسم المستخدم"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" style="color: #777; font-weight: normal;">كلمة المرور</label>
                        <input type="text" name="password" id="password" placeholder="كلمة المرور" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role" style="color: #777; font-weight: normal;">نوع المستخدم</label>
                        <select name="role" id="role" class="form-control">
                            <?php
                            foreach ($roles as $role) {
                                echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <input type="submit" value="أضف" class="btn btn-info" style="width: 100px;">
                </form>
            </div>

            <div class="section2">
                <h3>الأعضاء المسجلين</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('users') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="users-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                            <li><a href="#" class="btn btn-success" id="users-save-changes">حفظ التعديلات</a></li>
                        </ul>
                        <form id="users-search-form" method="GET" action="" style="{{$query ? "display: block;" : "display: none;"}}">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" placeholder="إسم أو أيبي المستخدم">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="users-ban">حظر</a></li>
                            <li><a href="#" id="users-delete">حذف</a></li>
                            <li>
                                <form action="">
                                    <select name="filter-role" id="filter-role" class="form-control">
                                        <option value="0">كل السوابر</option>
                                        <?php
                                        foreach ($roles as $role) {
                                            $sel = $role->id == $roleFilter ? "selected" : "";
                                            echo '<option '.$sel.' value="' . $role->id . '">' . $role->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </form>
                            </li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $users->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox"style="width: 17px; height: 17px;"></th>
                            <th>إسم المستخدم</th><th>تاريخ التسجيل</th><th>آخر زيارة</th><th>آخر أيبي</th><th>النوع</th><th>البلد</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($users->getCollection()->all() as $user) {
                            //if ($user->role == 'admin' && strlen($user->password) > 35) continue;
                            echo '<tr row_id="' . $user->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $user->id . '"></td>';
                            echo '<td>' . $user->name . '</td>';
                            echo '<td>' . $user->created_at . '</td>';
                            echo '<td>' . $user->last_login . '</td>';
                            echo '<td>' . $user->ip . '</td>';
                            echo '<td><select>';
                            foreach ($roles as $role) {
                                if ($user->role_id == $role->id)
                                    echo '<option selected="selected" value="' . $role->id . '">' . $role->name . '</option>';
                                else
                                    echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                            }
                            echo '</select></td>';
                            echo '<td class="user-country" country="'.$user->country.'"><img src="/images/flags/'.$user->country.'.png" /></td></tr>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="popup" id="users-ban-form">
                <form action="" method="POST">
                    <div class="form-group">
                        <input type="text" name="finishes_at" placeholder="المدة الزمنية" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <input type="text" name="cause" placeholder="السبب" class="form-control"/>
                    </div>
                    <button class="btn btn-default">رجوع</button>
                    <input type="submit" value="حظر" class="btn btn-danger">
                </form>
            </div>

        </div>
    </div>
@stop
@section('scripts')
    <script src="{{url('/js/socket.io.js')}}"></script>
    @include("scripts.users")
@stop