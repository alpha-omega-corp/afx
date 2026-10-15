<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#141210">

    <title>@yield('title', __('app.title'))</title>
    <meta name="description" content="@yield('description', __('footer.blurb'))">

    @php($route = Route::currentRouteName() ?? '')
    @if(str_starts_with($route, 'guest.'))
        <link rel="canonical" href="{{ url()->current() }}">
        @foreach(\App\Enums\Language::cases() as $lang)
            <link rel="alternate" hreflang="{{ $lang->value }}" href="{{ route(str_replace(['guest.fr.', 'guest.en.'], "guest.{$lang->value}.", $route)) }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ route(str_replace('guest.en.', 'guest.fr.', $route)) }}">
    @endif

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/js/app.js'])
</head>
<body>

<a class="skip-link" href="#main">{{ __('app.skip') }}</a>

<x-site-banner/>

@include('components.navigation')

<main id="main" class="app-main">
    @yield('content')
</main>

<x-footer/>

<x-modal.index
    :name="Modal::APP_LOGIN"
    :action="Action::CREATE"
    :title="__('app.login')"
    :route="route('auth.login')"
>
    <x-forms.input :icon="Icon::EMAIL" :label="__('form.email')" :required="true" name="email" type="email"/>
    <x-forms.input :icon="Icon::PASSWORD" :label="__('form.password')" :required="true" name="password" type="password"/>
</x-modal.index>

</body>
</html>
