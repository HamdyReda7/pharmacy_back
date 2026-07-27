<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @vite([
        'resources/css/app.css',
        'resources/css/dashboard.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-light">

<div class="container">

    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-5">

                        <h2 class="fw-bold">

                            {{ config('app.name') }}

                        </h2>

                        <p class="text-muted">

                            @yield('subtitle')

                        </p>

                    </div>

                    @yield('content')

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>