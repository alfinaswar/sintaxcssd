@extends('layouts.app-auth')

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head kt-ribbon kt-ribbon--right">
        <div class="kt-ribbon__target" style="top: 10px; right: -2px;">Aset</div>
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Sign In
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <form class="kt-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="kt-portlet__body">
                <div class="kt-login__signin">
                    <div class="form-group">
                        <label>Username</label>
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}" required placeholder="Username"
                            autocomplete="username" autofocus>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input id="password" type="password"
                            class="form-control form-control-last @error('password') is-invalid @enderror"
                            name="password" placeholder="Password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="kt-login__extra">

                    </div>
                    <div class="kt-login__actions">
                        {{-- <button type="submit" class="btn btn-brand btn-pill btn-elevate">Sign
                                In</button> --}}
                        <button type="submit" class="btn btn-block btn-brand btn-elevate btn-elevate-air ">Sign
                            In<i class="fa fa-sign-in-alt ml-2"></i></button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<div class="kt-portlet">
    <!--begin::Form-->

    <!--end::Form-->
</div>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

<div class="card-body">
    <form method="POST" action="{{ route('login') }}">


        <div class="row mb-3">
            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('username Address') }}</label>

            <div class="col-md-6">
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                    name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6 offset-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
                @endif
            </div>
        </div>
    </form>
</div>
</div>
</div>
</div>
</div> --}}
@endsection