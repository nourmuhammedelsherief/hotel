@extends('admin.layouts.master')

@section('title')
    المشرفين
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/datatables.bootstrap-rtl.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/profile-rtl.min.css') }}">
@endsection

@section('page_header')
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ url('admin/home') }}">لوحة التحكم</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('admins.index') }}">المشرفين</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>عرض المشرفين</span>
            </li>
        </ul>
    </div>

    <h1 class="page-title"> عرض مشرف
        {{--<small>عرض جميع المشرفين</small>--}}
    </h1>
@endsection

@section('content')
    @if(session()->has('msg'))

        <p class="alert alert-success" style="width: 100%">

            {{ session()->get('msg') }}

        </p>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="portlet light profile-sidebar-portlet ">
                <div class="profile-userpic">
                    <img src="{{ $data->image ? URL::asset('/public/uploads/admins/'.$data->image) : URL::asset('public/images/unknown.png') }}" class="img-responsive" alt="{{ $data->username }}"> </div>
                <div class="profile-usertitle" style="padding-bottom: 30px;">
                    <div class="profile-usertitle-name"> {{ $data->name }} </div>
                    <div class="profile-usertitle-job" style="text-transform: none;"> {{ $data->email }} </div>



                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="portlet light ">
                {{--<h3 class="profile-desc-title">مواصفات المستخدم</h3>--}}
                <br>
                <div>
                    <div class="profile-usertitle-job">  الهاتف : {{ $data->phone }} </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="portlet light ">
                {{--<h3 class="profile-desc-title">مواصفات الشريك</h3>--}}
                <br>
                <div>
                    <div class="profile-usertitle-job"> المجموعة : {{ $role->role_name }}  </div>
                    <div class="profile-usertitle-job"> {{ $data->created_at->format('Y-m-d g:i A') }} </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('body').addClass('page-container-bg-solid');
        });
    </script>

@endsection

