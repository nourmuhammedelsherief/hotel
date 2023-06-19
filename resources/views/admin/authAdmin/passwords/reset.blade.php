@extends('admin.authAdmin.master')

@section('title')
    إعادة ضبط كلمة المرور
@endsection
@section('content')
    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <h3 class="font-green">إعادة ضبط كلمة المرور</h3>
        <div class="form-group">
            <input id="email" class="form-control placeholder-no-fix{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" autocomplete="off" placeholder="البريد الالكتروني" name="email" value="{{ old('email') }}" required autofocus/>
            @if ($errors->has('email'))
                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <span> {{ $errors->first('email') }}</span>
                </div>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">كلمة المرور</label>
            <input id="password" class="form-control placeholder-no-fix{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" autocomplete="off"  placeholder="كلمة المرور" name="password" required />
            @if ($errors->has('password'))

                <div class="alert alert-danger">
                    <button class="close" data-close="alert"></button>
                    <span> {{ $errors->first('password') }}</span>
                </div>
            @endif
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">إعادة كلمة المرور</label>
            <input id="password-confirm" class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="إعادة كلمة المرور" name="password_confirmation" required /> </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase pull-right">ارسال</button>
        </div>
    </form>
@endsection
