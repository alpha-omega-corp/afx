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

        <div class="app-footer__bottom">
            <span>&copy; {{ date('Y') }} {{ __('app.title') }}</span>

            @auth
                <a class="app-footer__admin" href="{{ route('admin.home') }}">Admin</a>
            @else
                <button type="button" class="app-footer__admin"
                        data-bs-toggle="modal"
                        data-bs-target="#{{ \App\Helpers\ModalHelper::getId(Modal::APP_LOGIN, Action::CREATE, null) }}">
                    {{ __('app.login') }}
                </button>
            @endauth
        </div>
    </div>
</footer>
