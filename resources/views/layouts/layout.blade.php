<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <title>@yield('title', 'Zero Diffusion')</title>
</head>
<body>
    <header>
        <p><b>HuggingFace Stable Diffusion Pipeline WebUI</b> by <a href="https://zrproject.id/" target="blank">ZRProject</a></p>
    </header>
    
    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>