<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Record System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="page-wrapper">

        @if(session('success'))
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
                <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @yield('content')

    </div>
</body>
</html>
