<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>

    @vite([
        'resources/css/app.css',
        'resources/css/dashboard.css',
        'resources/css/categories.css',
        'resources/js/app.js'
    ])
</head>

<body>

<div class="dashboard-layout">

    @include('layouts.partials.sidebar')

    <div class="content">

        @include('layouts.partials.navbar')

        <main class="page-content">

            @yield('content')

        </main>

    </div>

</div>

</body>

</html>