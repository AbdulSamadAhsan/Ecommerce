<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Auth' }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @livewireStyles
</head>

<body style="background:linear-gradient(135deg,#0f172a,#2563eb);min-height:100vh;">

    {{ $slot }}

    @livewireScripts

</body>

</html>
