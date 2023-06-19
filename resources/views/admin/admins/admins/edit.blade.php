@extends('admin.lteLayout.master')

@section('title')
    المشرفين
@endsection

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @lang('messages.edit') @lang('messages.admins')
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('/admin/admins')}}">
                                @lang('messages.admins')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    @if(session()->has('msg'))

        <p class="alert alert-success" style="width: 100%">

            {{ session()->get('msg') }}

        </p>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.admins') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form-horizontal" method="post" action="{{ url('/admin/admins/'. $data->id) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="username" class="col-lg-3 control-label">الاسم</label>
                                    <div class="col-lg-9">
                                        <input id="username" name="name" type="text" value="{{ $data->name }}" class="form-control" placeholder="الاسم">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-lg-3 control-label">الايميل</label>
                                    <div class="col-lg-9">
                                        <input id="email" name="email" type="email" value="{{ $data->email }}" class="form-control" placeholder="الايميل">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">الهاتف</label>
                                    <div class="col-lg-9">
                                        <input id="phone" name="phone" type="text" value="{{ $data->phone }}" class="form-control" placeholder="الهاتف">
                                        @if ($errors->has('phone'))
                                            <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('phone') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="col-lg-3 control-label">الصلاحية</label>
                                    <div class="col-lg-9">
                                        <select name="role" class="form-control">
                                            <option disabled selected> حدد صلاحيه المشرف  </option>
                                            <option value="admin" {{$data->role == 'admin' ? 'selected' : ''}}>مدير</option>
                                            <option value="sales" {{$data->role == 'sales' ? 'selected' : ''}}>مبيعات</option>
                                            <option value="developer" {{$data->role == 'developer' ? 'selected' : ''}}>مطور</option>
                                        </select>
                                        @if ($errors->has('role'))
                                            <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('role') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-lg-3 control-label">{{ trans('dashboard.entry.password') }}</label>
                                    <div class="col-lg-9">
                                        <input id="password" name="password" type="password" value="" class="form-control" placeholder="{{trans('dashboard.entry.password')}}">
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation" class="col-lg-3 control-label">{{ trans('dashboard.entry.password_confirmation') }}</label>
                                    <div class="col-lg-9">
                                        <input id="password_confirmation" name="password_confirmation" type="password" value="" class="form-control" placeholder="{{trans('dashboard.entry.password_confirmation')}}">
                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                       <strong style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div style="clear: both"></div>

                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-lg-2 col-lg-offset-10">
                                            {{--<button type="submit" class="btn green btn-block">حفظ</button>--}}
                                            <input class="btn btn-primary" type="submit" value="حفظ" onclick="this.disabled=true;this.value='تم الارسال, انتظر...';this.form.submit();" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>


    {{--{!! Form::close() !!}--}}
@endsection

@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
@endsection

