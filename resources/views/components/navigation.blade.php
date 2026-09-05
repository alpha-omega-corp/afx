<div class="nav-sentinel" aria-hidden="true"></div>

<nav class="app-navigation" x-data="{ open: false }" :class="{ 'is-open': open }" aria-label="{{ __('nav.primary') }}">
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

        <div class="app-navigation__bar-locale">
            <x-locale/>
        </div>

        <button
            type="button"
            class="app-navigation__toggle"
            @click="open = !open"
            :aria-expanded="open ? 'true' : 'false'"
            aria-controls="mobile-nav"
        >
            <span class="visually-hidden">{{ __('nav.menu_toggle') }}</span>
            <template x-if="!open">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
            </template>
            <template x-if="open">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </template>
        </button>
    </div>

    <div class="app-navigation__panel" id="mobile-nav" x-show="open" x-cloak @keydown.escape.window="open = false">
        <ul>
            @include('app.partials.navigation')
        </ul>
    </div>
</nav>
