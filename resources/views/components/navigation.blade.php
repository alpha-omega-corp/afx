<div class="nav-sentinel" aria-hidden="true"></div>

{{-- The sidebar sits outside <nav> on purpose. The bar carries a
     backdrop-filter once it is stuck, and a filtered ancestor becomes the
     containing block for position:fixed descendants — which would pin the
     sidebar to the height of the bar.

     The watcher closes it on the way up to lg, so a resize cannot leave the
     page scroll-locked behind a sidebar that no longer shows. --}}
<div
    x-data="{ open: false }"
    x-init="matchMedia('(min-width: 992px)').addEventListener('change', (e) => e.matches && (open = false))"
    @keydown.escape.window="open = false"
>
    <nav class="app-navigation" aria-label="{{ __('nav.primary') }}">
        <div class="container app-navigation__inner">
            <a href="{{ route(__('route.home')) }}" class="app-navigation__logo">
                {{ __('app.title') }}
            </a>

            <div class="app-navigation__right">
                <ul class="app-navigation__menu">
                    @include('app.partials.navigation')
                </ul>

                <x-locale/>
            </div>

            <button
                type="button"
                class="app-navigation__toggle"
                @click="open = true"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mobile-nav"
            >
                <span class="visually-hidden">{{ __('nav.menu_toggle') }}</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M10 17h10"/></svg>
            </button>
        </div>
    </nav>

    {{-- Tapping the dimmed page closes the sidebar. It leaves faster than the
         panel does, so the light is back before the panel has gone. --}}
    <div
        class="app-navigation__scrim"
        x-show="open"
        x-cloak
        x-transition:enter="app-navigation__scrim--enter"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="app-navigation__scrim--leaving"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        aria-hidden="true"
    ></div>

    {{-- The sidebar on a phone: the full height of the screen, sliding in from
         the right edge over the dimmed page. The pages large, each with its
         plate number and an arrow; a table, the number and the hours at its
         foot, where the thumb is.
         x-trap.noscroll keeps the keyboard inside, returns focus to the
         toggle on close, and locks the page behind it — without that last
         part the body scrolls under the open menu on iOS. --}}
    <div
        class="app-navigation__panel"
        id="mobile-nav"
        role="dialog"
        aria-modal="true"
        aria-label="{{ __('nav.primary') }}"
        :class="{ 'is-open': open }"
        x-trap.noscroll="open"
    >
        <div class="app-navigation__panel-head">
            <a href="{{ route(__('route.home')) }}" class="app-navigation__panel-brand">{{ __('app.title') }}</a>

            <button type="button" class="app-navigation__close" @click="open = false">
                <span class="visually-hidden">{{ __('nav.menu_close') }}</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>

        <ul class="app-navigation__panel-nav">
            @include('app.partials.navigation', ['drawer' => true])
        </ul>

        {{-- A table and a telephone number: the two things a guest opens
             this menu to get to, without a second tap. --}}
        <div class="app-navigation__panel-foot">
            <a href="https://wa.me/+41786857845" class="btn btn-primary" target="_blank" rel="noopener">
                {{ __('app.reserve') }}
            </a>

            <a class="app-navigation__panel-tel" href="tel:+41227761029">022 776 10 29</a>

            <p class="app-navigation__panel-hours">
                @foreach(App\Support\Opening::schedule() as $line)
                    <span>{{ $line }}</span>
                @endforeach
            </p>

            {{-- A setting, not a destination: last, under a hairline, with
                 the label it never had room for in the bar. --}}
            <div class="app-navigation__panel-locale">
                <span class="app-navigation__panel-label">{{ __('nav.language') }}</span>
                <x-locale/>
            </div>
        </div>
    </div>
</div>
