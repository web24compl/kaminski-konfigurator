<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LdEpHIpAAAAAAPzYkxy4y1RsZYFdzxFvUX3iMnt"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&amp;family=Poppins:wght@300;400;600&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    @include('partials.header')
    <div id="app"></div>
    @include('partials.footer')
</body>
<script defer>
    window.questions = @json($questions)
</script>
</html>
