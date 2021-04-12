@extends('fadmin.master')

@section('content')
    <div id="main-content" class="settings-page">

        <div class="row">
            <div id="settings-section">

                @if(Session::has("success_mesg"))
                    <div class="alert alert-success">{{Session::get("success_mesg")}}</div>
                @endif

                <div class="section">
                    <form action="/fadmn/developer/{{$token}}/developerSettings" method="POST">

                        {{csrf_field()}}

                        {{--<div class="form-group">--}}
                            {{--<label class="form-label" for="settings-javascript">Javascript</label>--}}
                            {{--<textarea class="form-control" rows="20" name="javascript" id="settings-javascript" placeholder="أكواد الجافاسكريبت">{{$settings->javascript}}</textarea>--}}
                        {{--</div>--}}

                        <div class="form-group">
                            <label class="form-label" for="settings-css">CSS</label>
                            <textarea class="form-control" rows="20" name="css" id="settings-css" placeholder="أكواد السي أس أس">{{$settings->css}}</textarea>
                        </div>

                        <input type="submit" value="حفظ" class="btn btn-success"/>

                    </form>
                </div>

            </div>
        </div>

    </div>
@endsection