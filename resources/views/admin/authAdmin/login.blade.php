<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('messages.control_panel') | @lang('messages.login')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{asset('lte/plugins/fontawesome-free/css/all.min.css')}}">

    <link rel="stylesheet" href="{{asset('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{asset('lte/dist/css/adminlte.min.css?v=3.2.0')}}">


<style>
        .login-logo .change-lang{
            position: absolute;
            top: 24px;
            left: 17px;
            font-size: 1rem;
            font-weight: bold;
        }
</style>

    <script nonce="376f936e-348d-4d08-9793-4830326ff13f">(function(w,d){!function(a,e,t,r){a.zarazData=a.zarazData||{},a.zarazData.executed=[],a.zaraz={deferred:[]},a.zaraz.q=[],a.zaraz._f=function(e){return function(){var t=Array.prototype.slice.call(arguments);a.zaraz.q.push({m:e,a:t})}};for(const e of["track","set","ecommerce","debug"])a.zaraz[e]=a.zaraz._f(e);a.addEventListener("DOMContentLoaded",(()=>{var t=e.getElementsByTagName(r)[0],z=e.createElement(r),n=e.getElementsByTagName("title")[0];for(a.zarazData.c=e.cookie,n&&(a.zarazData.t=e.getElementsByTagName("title")[0].text),a.zarazData.w=a.screen.width,a.zarazData.h=a.screen.height,a.zarazData.j=a.innerHeight,a.zarazData.e=a.innerWidth,a.zarazData.l=a.location.href,a.zarazData.r=e.referrer,a.zarazData.k=a.screen.colorDepth,a.zarazData.n=e.characterSet,a.zarazData.o=(new Date).getTimezoneOffset(),a.zarazData.q=[];a.zaraz.q.length;){const e=a.zaraz.q.shift();a.zarazData.q.push(e)}z.defer=!0,z.referrerPolicy="origin",z.src="/cdn-cgi/zaraz/s.js?z="+btoa(encodeURIComponent(JSON.stringify(a.zarazData))),t.parentNode.insertBefore(z,t)}))}(w,d,0,"script");})(window,document);</script></head>
<body class="hold-transition login-page">

<div class="login-box">
    @if (session('An_error_occurred'))
        <div class="alert alert-success">
            {{ session('An_error_occurred') }}
        </div>
    @endif
    @if (session('warning_login'))
        <div class="alert alert-danger">
            {{ session('warning_login') }}
        </div>
    @endif
    <div class="login-logo">
        <a href="{{url('locale/' . (app()->getLocale() == 'ar' ? 'en' : 'ar'))}}" class="change-lang" style="{{app()->getLocale() == 'en' ? 'left:unset;right:17px !important;top:24px !important;' : ''}}">{{app()->getLocale() == 'ar' ? 'English' : 'عربي'}}</a>
        <a href="{{route('admin.login')}}">@lang('messages.login')</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <h4 class="text-center mb-4">{{trans('dashboard.dash_admin')}}</h4>
            <form action="{{route('admin.login.submit')}}" method="post">
                @csrf
                <div class="input-group mb-3">
{{--                    <input type="email" class="form-control" placeholder="Email">--}}
                    <input class="form-control form-control-solid placeholder-no-fix{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" autocomplete="off" placeholder="@lang('messages.email')" name="email" value="{{ old('email') }}"  required autofocus />
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @if ($errors->has('email'))

                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ $errors->first('email') }}</span>
                        </div>
                    @endif
                </div>
                <div class="input-group mb-3">
{{--                    <input type="password" class="form-control" placeholder="Password">--}}
                    <input class="form-control form-control-solid placeholder-no-fix{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" autocomplete="off" placeholder="@lang('messages.password')" name="password" required  />
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @if ($errors->has('password'))
                        <div class="alert alert-danger">
                            <button class="close" data-close="alert"></button>
                            <span> {{ $errors->first('password') }}</span>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input   type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember">
                                @lang('messages.remember_me')
                            </label>
                        </div>
                    </div>

                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            @lang('messages.signIn')
                        </button>
                    </div>

                </div>
            </form>
{{--            <div class="social-auth-links text-center mb-3">--}}
{{--                <p>- OR -</p>--}}
{{--                <a href="#" class="btn btn-block btn-primary">--}}
{{--                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook--}}
{{--                </a>--}}
{{--                <a href="#" class="btn btn-block btn-danger">--}}
{{--                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+--}}
{{--                </a>--}}
{{--            </div>--}}


{{--            <p class="mb-0">--}}
{{--                <a href="register.html" class="text-center">Register a new membership</a>--}}
{{--            </p>--}}
        </div>

    </div>
</div>


<script src="{{asset('lte/plugins/jquery/jquery.min.js')}}"></script>

<script src="{{asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<script src="{{asset('lte/dist/js/adminlte.min.js?v=3.2.0')}}"></script>
</body>
</html>
