<!DOCTYPE html>
<html lang="en">
<head>
    <title>Recognition person from webcam</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    @vite('resources/css/app.scss')
    @stack('styles')
</head>
<body>

<div class="container">
    @yield('content')
</div>

@vite('resources/js/app.js')
@stack('scripts')

</body>
</html>
