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

{{-- `isDrawer` is what separates the two lives of the rail. Above lg the same
     <aside> is the permanent wall: it must not be a dialog, must not trap
     focus and must not lock the page scroll. Every one of those behaviours is
     therefore gated on `isDrawer && nav`, never on `nav` alone — and the
     watcher closes the drawer on the way up so a resize cannot leave the
     scroll locked behind a rail that is no longer overlaying anything. --}}
<div
    class="admin-shell"
    x-data="{
        nav: false,
        isDrawer: ! window.matchMedia('(min-width: 992px)').matches,
        watchWidth() {
            const rail = window.matchMedia('(min-width: 992px)');
            const sync = () => {
                this.isDrawer = ! rail.matches;
                if (rail.matches) this.nav = false;
            };
            rail.addEventListener('change', sync);
            sync();
        },
    }"
    x-init="watchWidth()"
    @keydown.escape.window="if (nav) { nav = false; $nextTick(() => $refs.navToggle?.focus()); }"
>

    <a href="#admin-main" class="skip-link">{{ __('app.skip') }}</a>

    {{-- Two modifiers, and the reason for each.
         `.noscroll` locks the page behind the drawer; it is gated on
         `isDrawer && nav` so the docked rail above lg never traps anything.
         NOT `.inert`: that modifier marks every sibling of the trapped element
         aria-hidden, and the scrim is one of them — tap-to-close would stop
         being announced. The rest of the page is made inert by hand on
         .admin-shell__body below.
         `.noreturn` because that hand-rolled inert is what breaks focus-trap's
         own restore: the moment the body goes inert the toggle inside it is
         blurred, so by the time the trap activates the node it would return to
         is <body>. The two close paths put the focus back on the toggle
         themselves, once inert has been lifted. --}}
    <aside
        id="admin-rail"
        class="admin-shell__sidebar"
        :class="{ 'is-open': nav }"
        x-trap.noscroll.noreturn="isDrawer && nav"
        :role="isDrawer ? 'dialog' : null"
        :aria-modal="isDrawer && nav ? 'true' : null"
        aria-label="{{ __('admin.title') }}"
    >
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

    {{-- Closes the drawer when the sidebar is overlaid on small screens. It
         fades in with the rail and fades out faster than it, so the light is
         back before the panel has finished leaving. --}}
    <div
        class="admin-shell__scrim"
        x-show="nav"
        x-cloak
        x-transition:enter="admin-shell__scrim--enter"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="admin-shell__scrim--leaving"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="nav = false; $nextTick(() => $refs.navToggle?.focus());"
        aria-hidden="true"
    ></div>

    {{-- `true` / `null` and never `false`: inert is not one of the boolean
         attributes Alpine knows, and inert="false" is still inert. --}}
    <div class="admin-shell__body" :inert="(isDrawer && nav) ? true : null">
        <header class="admin-topbar">
            <button
                type="button"
                class="admin-topbar__toggle"
                x-ref="navToggle"
                aria-controls="admin-rail"
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

        {{-- The bento container itself. Every direct child of @yield('content')
             is therefore a grid cell; the span classes live on those children
             and never on this element. --}}
        <main id="admin-main" class="admin-shell__main admin-bento" tabindex="-1">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
