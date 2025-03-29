<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="w-screen h-screen flex items-center justify-center">
{{ $slot }}
</body>
</html>
