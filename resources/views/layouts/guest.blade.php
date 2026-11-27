<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="is-framed">
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
    {{-- FIGMA-CAPTURE-TEMP: remove after the Figma import --}}
    @if(app()->isLocal())
        <script src="https://mcp.figma.com/mcp/html-to-design/capture.js" async></script>
        <script>if (location.hash.includes('figmacapture')) addEventListener('load', () => setTimeout(() => document.querySelectorAll('[data-reveal]').forEach((el) => el.classList.add('is-in')), 200));</script>
    @endif
</head>
<body>

<a class="skip-link" href="#main">{{ __('app.skip') }}</a>

{{-- The whole site stands in one centred column, its edges drawn as two
     hairlines down the screen: the banner, the bar, every hero photograph
     and the footer stop at them, never at the window's edge. --}}
<div class="site-frame">
    <x-site-banner/>

    <x-top-bar/>

    @include('components.navigation')

    <main id="main" class="app-main">
        @yield('content')
    </main>

    <x-footer/>
</div>

<x-modal.index
    :name="Modal::APP_LOGIN"
    :action="Action::CREATE"
    :title="__('app.login')"
    :route="route('auth.login')"
>
    <x-forms.input :icon="Icon::EMAIL" :label="__('form.email')" :required="true" name="email" type="email"/>
    <x-forms.input :icon="Icon::PASSWORD" :label="__('form.password')" :required="true" name="password" type="password"/>
</x-modal.index>

{{-- Modals a page brings (x-page's preview), outside the frame so no
     stacking context in it can hold them under the backdrop. --}}
@stack('modals')

</body>
</html>
