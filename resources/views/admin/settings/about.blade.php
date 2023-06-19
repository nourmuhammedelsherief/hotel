@extends('admin.layouts.master')

@section('title')
    من نحن
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <style>
        #map {
            height: 500px;
            width: 1000px;
        }
    </style>
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{url('/admin/home')}}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/admin/about_us')}}">من نحن </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>من نحن  </span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> من نحن
        <small>من نحن      </small>
    </h1>
@endsection

@section('content')
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    @include('flash::message')
    <div class="row">
        <div class="col-md-12">

            <!-- BEGIN PROFILE CONTENT -->
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <form role="form" action="{{route('store_about')}}" method="post" enctype="multipart/form-data">
                                <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                <div class="portlet-body">

                                    <div class="tab-content">
                                        <div class="form-group">
                                            <label class="control-label"> العنوان </label>
                                            <input name="title" type="text" class="form-control" value="{{$about->title}}" placeholder="اكتب عنوان من نحن ">
                                            @if ($errors->has('title'))
                                                <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> المحتوي </label>
                                            <textarea name="content" id="description">
                                                {{$about->content}}
                                            </textarea>
                                            @if ($errors->has('content'))
                                                <span class="help-block">
                                                       <strong style="color: red;">{{ $errors->first('content') }}</strong>
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="form-body">
                                            <div class="form-group ">
                                                <label class="control-label col-md-3"> صوره من نحن </label>
                                                <div class="col-md-9">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                                            @if($about->photo !==null)
                                                                <img   src='{{ asset("uploads/settings/$about->photo") }}'>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <span class="btn red btn-outline btn-file">
                                                                <span class="fileinput-new"> اختر الصورة </span>
                                                                <span class="fileinput-exists"> تغيير </span>
                                                                <input type="file" name="photo"> </span>
                                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> إزالة </a>



                                                        </div>
                                                    </div>
                                                    @if ($errors->has('photo'))
                                                        <span class="help-block">
                                                               <strong style="color: red;">{{ $errors->first('photo') }}</strong>
                                                            </span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="margiv-top-10">
                                    <div class="form-actions">
                                        <button type="submit" class="btn green" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();">حفظ</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PROFILE CONTENT -->
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

    <script src="{{ URL::asset('admin/ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
@endsection
