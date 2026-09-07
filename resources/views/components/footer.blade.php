<footer class="app-footer">
    <div class="container">
        <div class="app-footer__grid">
            <div class="border-bottom border-primary pb-4">
                <p class="app-footer__wordmark">{{ __('app.title') }}</p>
                <p class="app-footer__blurb">{{ __('footer.blurb') }}</p>
            </div>

            <div>
                <h2 class="app-footer__title">{{ __('footer.sitemap') }}</h2>
                <ul>
                    <li><a class="app-footer__link" href="{{ route(__('route.home')) }}">{{ __('nav.home') }}</a></li>
                    <li><a class="app-footer__link" href="{{ route(__('route.menu')) }}">{{ __('nav.menu') }}</a></li>
                    <li><a class="app-footer__link" href="{{ route(__('route.restaurant')) }}">{{ __('nav.restaurant') }}</a></li>
                    <li><a class="app-footer__link" href="{{ route(__('route.hotel')) }}">{{ __('nav.hotel') }}</a></li>
                    <li><a class="app-footer__link" href="{{ route(__('route.contact')) }}">{{ __('nav.contact') }}</a></li>
                </ul>
            </div>

            <div>
                <h2 class="app-footer__title">{{ ucfirst(__('nav.contact')) }}</h2>
                <ul>
                    <li><a class="app-footer__link" href="https://www.google.ch/maps/place/Grand-Rue+31,+1297+Founex/@46.3325566,6.1902006,17z/" target="_blank" rel="noopener">Grand'Rue 31, 1297 Founex</a></li>
                    <li><a class="app-footer__link" href="tel:+41227761029">022 776 10 29</a></li>
                    <li><a class="app-footer__link" href="mailto:aubergedefounex@bluewin.ch">aubergedefounex@bluewin.ch</a></li>
                </ul>
            </div>

            <div>
                <h2 class="app-footer__title">{{ __('footer.social') }}</h2>
                <div class="app-footer__social">
                    <a href="https://www.facebook.com/AubergeFounex" target="_blank" rel="noopener">
                        <span class="visually-hidden">Facebook</span>
                        <x-icon.facebook/>
                    </a>
                    <a href="https://www.instagram.com/auberge_de_founex/" target="_blank" rel="noopener">
                        <span class="visually-hidden">Instagram</span>
                        <x-icon.instagram/>
                    </a>
                </div>
            </div>
        </div>

        {{-- The schedule closes the footer: the last thing read before the
             copyright, and the thing a guest most often comes back for. --}}
        <div class="app-footer__hours">
            <h2 class="app-footer__title">{{ __('footer.hours') }}</h2>

            {{-- Written in Administration → Ouverture, and the same list the
                 plat du jour reads to know which days it can be served on. --}}
            <p class="app-footer__hours-lines">
                @foreach(App\Support\Opening::schedule() as $line)
                    <span>{{ $line }}</span>
                @endforeach
            </p>
        </div>

        <div class="app-footer__bottom">
            <div class="app-footer__meta">
                <span>&copy; {{ date('Y') }} {{ __('app.title') }}</span>

                {{-- Who built the site. The mark is their own, unmodified and
                     served from public/ so it can be replaced without a
                     rebuild; the domain beside it does the reading, since a
                     mark at footer size cannot. --}}
                <a class="app-footer__credit" href="https://apdigital.ch" target="_blank" rel="noopener">
                    <span>{{ __('footer.credit') }}</span>

                    @if(file_exists(public_path('apdigital.svg')))
                        <img
                            class="app-footer__credit-logo"
                            src="{{ asset('apdigital.svg') }}"
                            alt=""
                            width="16"
                            height="16"
                        >
                    @endif

                    <span class="app-footer__credit-name">apdigital.ch</span>
                </a>
            </div>

            @auth
                {{-- The back office lives here rather than in the guest
                     navigation: it is a staff door, not a page of the site. --}}
                <nav class="app-footer__admin-nav" aria-label="{{ __('admin.title') }}">
                    @foreach(\App\Support\AdminSections::all() as $section)
                        <a class="app-footer__admin" href="{{ route($section['route']) }}">{{ ucfirst($section['label']) }}</a>
                    @endforeach

                    {{-- The way out gets the same pill as the way in, with the
                         lock open: the two are the same door, and this one was
                         the faintest thing on the page. --}}
                    <a class="app-footer__login" href="{{ route('auth.logout') }}">
                        @svg(Icon::OPEN->value, 'app-footer__login-icon')
                        <span>{{ ucfirst(__('nav.logout')) }}</span>
                    </a>
                </nav>
            @else
                {{-- The same staff door, but a door: as a bare word at the end
                     of the copyright line it read as fine print and went
                     unfound. Quiet enough to stay out of a guest's way, drawn
                     clearly enough to be seen when it is looked for. --}}
                <button type="button" class="app-footer__login"
                        data-bs-toggle="modal"
                        data-bs-target="#{{ \App\Helpers\ModalHelper::getId(Modal::APP_LOGIN, Action::CREATE, null) }}">
                    @svg(Icon::PASSWORD->value, 'app-footer__login-icon')
                    <span>{{ __('app.login') }}</span>
                </button>
            @endauth
        </div>
    </div>
</footer>
