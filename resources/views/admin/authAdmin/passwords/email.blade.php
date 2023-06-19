@extends('admin.authAdmin.master')

@section('title')
    نسيت كلمة المرور؟
@endsection
@section('content')
    <!-- BEGIN FORGOT PASSWORD FORM -->
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.password.email') }}">
        @csrf

        <h3 class="font-green">نسيت كلمة المرور؟</h3>
        <p>ادخل بريدك الالكتروني لاستعاده كلمة المرور الخاصة بك </p>
        <div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="البريد الالكتروني" name="email" value="{{ old('email') }}" required autofocus />
            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <span> {{ $errors->first('email') }}</span>
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase pull-right">ارسال</button>
        </div>
    </form>
    <!-- END FORGOT PASSWORD FORM -->
@endsection
