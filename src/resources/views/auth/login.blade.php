<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{!! setting('app_name', 'Kanaung SMS Default'); !!}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    @yield('css')

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/home') }}">{!! setting('app_name', 'Kanaung SMS Default'); !!}</a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign In</p>

        <form method="post" action="{{ url('/login') }}">
            {!! csrf_field() !!}

            <div class="form-group has-feedback {{ $errors->has('csrf_error') ? ' has-error' : '' }}">
                <span class="form-control-feedback"></span>
                @if ($errors->has('csrf_error'))
                    <span class="help-block">
                    <strong>{{ $errors->first('csrf_error') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif

            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="">

                            <input class="magic-checkbox" type="checkbox" name="remember" id="remember">
                            <label for="remember">Remember Me </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="{{ url('/password/reset') }}">I forgot my password</a><br>
        <a href="{{ url('/register') }}" class="text-center">Register a new membership</a>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

    <!-- Combined vendor js -->
    <script src="{{ elixir('js/vendor.js') }}"></script>

    <!-- app script -->
    <script src="{{ elixir('js/app.js') }}"></script>

    @yield('scripts')

</body>
</html>
