<footer class="app-footer">
    <div class="container">
        <div class="app-footer__grid">
            <div>
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

            <p class="app-footer__hours-lines">
                <span>{{ __('footer.hours_week') }}</span>
                <span>{{ __('footer.hours_closed') }}</span>
            </p>
        </div>

        <div class="app-footer__bottom">
            <span>&copy; {{ date('Y') }} {{ __('app.title') }}</span>

            @auth
                {{-- The back office lives here rather than in the guest
                     navigation: it is a staff door, not a page of the site. --}}
                <nav class="app-footer__admin-nav" aria-label="{{ __('admin.title') }}">
                    @foreach(\App\Support\AdminSections::all() as $section)
                        <a class="app-footer__admin" href="{{ route($section['route']) }}">{{ ucfirst($section['label']) }}</a>
                    @endforeach

                    <a class="app-footer__admin app-footer__admin--out" href="{{ route('auth.logout') }}">{{ ucfirst(__('nav.logout')) }}</a>
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
