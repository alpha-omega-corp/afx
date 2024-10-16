<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{__('app.title')}}</title>

    @vite(['resources/js/app.js'])

</head>
<body>

@include('components.navigation')
@yield('content')

</body>

<x-footer />

</html>
