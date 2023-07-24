<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MiniCommerce') }}</title>

    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
</head>

<body>
    <div class="container">
        <div class="row align-items-center justify-content-center" style="height: 100vh;">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="cs-text-primary fw-bolder text-center">{{ __('Welcome to Alibaba') }}</h1>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="col-form-label text-muted">
                                    {{ __('E-Mail Address') }}
                                </label>

                                <div class="">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror input-bg-graylight" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="col-form-label text-muted">
                                    {{ __('Password') }}
                                </label>

                                <div class="">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror input-bg-graylight" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input input-bg-graylight" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}" style="font-size: 14px;">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn custom-btn cs-bg-primary text-white form-control">
                                    {{ __('Login') }}
                                </button>
                                <!-- <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
