@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.control_panel')
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('messages.control_panel')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">@lang('messages.control_panel')</a></li>
                        {{--                        <li class="breadcrumb-item active">Dashboard v1</li>--}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">

{{--                <div class="col-lg-3 col-6">--}}
{{--                    <!-- small box -->--}}
{{--                    <div class="small-box bg-success">--}}
{{--                        <div class="inner">--}}
{{--                            <h3>--}}
{{--                                {{\App\Models\Restaurant::whereHas('subscription',function ($q){--}}
{{--                                    $q->where('status' , 'active');--}}
{{--                                    $q->where('package_id' , 1);--}}
{{--                                    $q->where('type' , 'restaurant');--}}
{{--                                 })--}}
{{--                                 ->where('archive' , 'false')--}}
{{--                                 ->where('status' , 'active')--}}
{{--                                 ->count()--}}
{{--                                 }}--}}
{{--                            </h3>--}}

{{--                            <p>@lang('messages.restaurants')</p>--}}
{{--                        </div>--}}
{{--                        <div class="icon">--}}
{{--                            <i class="ion ion-person-add"></i>--}}
{{--                        </div>--}}
{{--                        <a href="{{url('/admin/restaurants/active')}}"--}}
{{--                           class="small-box-footer">@lang('messages.details')--}}
{{--                            <i class="fas fa-arrow-circle-right"></i></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
            <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
