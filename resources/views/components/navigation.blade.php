<div class="nav-sentinel" aria-hidden="true"></div>

{{-- The panel sits outside <nav> on purpose. The bar carries a backdrop-filter
     when it is stuck or open, and a filtered ancestor becomes the containing
     block for position:fixed descendants — which collapsed the panel to the
     height of the bar. --}}
<div x-data="{ open: false }" @keydown.escape.window="open = false">
    <nav class="app-navigation" :class="{ 'is-open': open }" aria-label="{{ __('nav.primary') }}">
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
                @click="open = !open"
                :aria-expanded="open ? 'true' : 'false'"
                aria-controls="mobile-nav"
            >
                <span class="visually-hidden" x-text="open ? @js(__('nav.menu_close')) : @js(__('nav.menu_toggle'))">{{ __('nav.menu_toggle') }}</span>
                <template x-if="!open">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </template>
                <template x-if="open">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </template>
            </button>
        </div>
    </nav>

    {{-- x-trap.noscroll keeps the keyboard inside the panel, returns focus to
         the toggle on close, and locks the page behind it — without that last
         part the body scrolls under the open menu on iOS. --}}
    <div
        class="app-navigation__panel"
        id="mobile-nav"
        x-show="open"
        x-cloak
        x-trap.noscroll="open"
        x-transition.opacity.duration.180ms
    >
        <div class="container app-navigation__panel-inner">
            <ul class="app-navigation__panel-nav">
                @include('app.partials.navigation')
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

                {{-- The switch lives here on a phone rather than in the bar,
                     where it spent the width the wordmark needed. It is a
                     setting, not a destination, so it sits last, under a
                     hairline, with the label it never had room for. --}}
                <div class="app-navigation__panel-locale">
                    <span class="app-navigation__panel-label">{{ __('nav.language') }}</span>
                    <x-locale/>
                </div>
            </div>
        </div>
    </div>
</div>
