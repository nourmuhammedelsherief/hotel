@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.settings')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.settings') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('settings.index')}}">
                                @lang('messages.settings')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-8">
                @include('flash::message')
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.settings') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('store_setting' , $setting->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.tentative_period') </label>
                                    <input name="tentative_period" type="number" class="form-control" value="{{$setting->tentative_period}}" placeholder="@lang('messages.tentative_period')">
                                    @if ($errors->has('tentative_period'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('tentative_period') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> الفتره التجريبيه للخدمات والفروع (يوم)</label>
                                    <input name="branch_service_tentative_period" type="number" class="form-control" value="{{$setting->branch_service_tentative_period}}" placeholder="حدد الفتره  التجريبيه للخدمات  والفروع بالايام">
                                    @if ($errors->has('branch_service_tentative_period'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_service_tentative_period') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> رقم الواتساب الخاص بتفعيل حساب المطعم </label>
                                    <input name="active_whatsapp_number" type="text" class="form-control" value="{{$setting->active_whatsapp_number}}" placeholder=" رقم الواتساب الخاص بتفعيل حساب المطعم ">
                                    @if ($errors->has('active_whatsapp_number'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('active_whatsapp_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> رقم الدعم الفني </label>
                                    <input name="technical_support_number" type="text" class="form-control" value="{{$setting->technical_support_number}}" placeholder="اكتب رقم الدعم الفني">
                                    @if ($errors->has('technical_support_number'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('technical_support_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label">رقم خدمة العملاء</label>
                                    <input name="customer_services_number" type="text" class="form-control" value="{{$setting->customer_services_number}}" placeholder="رقم خدمة العملاء">
                                    @if ($errors->has('customer_services_number'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('customer_services_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> قيمة الضريبة للموقع </label>
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <input name="tax" type="text" class="form-control" value="{{$setting->tax}}" placeholder="ادخل قيمة الضريبة المضافة للمطاعم والخدمات">
                                            @if ($errors->has('tax'))
                                                <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('tax') }}</strong>
                                        </span>
                                            @endif
                                        </div>
                                        <div class="col-sm-2">%</div>
                                    </div>

                                </div>

                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
