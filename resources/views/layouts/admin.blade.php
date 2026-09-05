<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('admin.title') }} — {{ __('app.title') }}</title>

    {{-- Endpoints the admin behaviours call. Declared here so the JS itself
         stays free of Blade and can live in resources/js/admin.js. --}}
    <script>
        window.AdminRoutes = {
            sortMenu: @json(route('admin.menu.sort')),
            removeMenuItems: @json(route('admin.menu.remove')),
            deleteGalleryItems: @json(route('gallery.delete')),
        };
    </script>

    @vite(['resources/js/app.js'])
</head>
<body class="admin">

<div class="admin-shell" x-data="{ nav: false }" @keydown.escape.window="nav = false">

    <a href="#admin-main" class="skip-link">{{ __('app.skip') }}</a>

    <aside class="admin-shell__sidebar" :class="{ 'is-open': nav }">
        <a href="{{ route(__('route.home')) }}" class="admin-shell__brand">
            <span class="admin-shell__brand-name">{{ __('app.title') }}</span>
            <span class="admin-shell__brand-label">{{ __('admin.title') }}</span>
        </a>

        <nav class="admin-nav" aria-label="{{ __('admin.nav_label') }}">
            @include('app.admin.partials.navigation')
        </nav>

        <div class="admin-shell__sidebar-footer">
            <a href="{{ route(__('route.home')) }}" class="admin-nav__link">
                @svg('heroicon-o-arrow-top-right-on-square', 'admin-nav__icon')
                <span>{{ __('admin.view_site') }}</span>
            </a>

            <a href="{{ route('auth.logout') }}" class="admin-nav__link">
                @svg('heroicon-o-arrow-left-on-rectangle', 'admin-nav__icon')
                <span>{{ ucfirst(__('nav.logout')) }}</span>
            </a>
        </div>
    </aside>

    {{-- Closes the drawer when the sidebar is overlaid on small screens. --}}
    <div class="admin-shell__scrim" x-show="nav" x-cloak @click="nav = false"></div>

    <div class="admin-shell__body">
        <header class="admin-topbar">
            <button
                type="button"
                class="admin-topbar__toggle"
                @click="nav = !nav"
                :aria-expanded="nav ? 'true' : 'false'"
            >
                <span class="visually-hidden">{{ __('admin.nav_toggle') }}</span>
                @svg('heroicon-o-bars-3')
            </button>

            <div class="admin-topbar__heading">
                <h1 class="admin-topbar__title">@yield('title')</h1>
            </div>

            @hasSection('actions')
                <div class="admin-topbar__actions">
                    @yield('actions')
                </div>
            @endif
        </header>

        <main id="admin-main" class="admin-shell__main">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
