<section class="visit" aria-labelledby="visit-heading">
    <div class="container">
        <h2 id="visit-heading" class="visually-hidden">{{ __('footer.visit') }}</h2>

        <div class="visit__grid">
            <div>
                <p class="visit__label">{{ __('footer.address') }}</p>
                <p class="visit__value">
                    <a href="https://www.google.ch/maps/place/Grand-Rue+31,+1297+Founex/@46.3325566,6.1902006,17z/"
                       target="_blank" rel="noopener">Grand'Rue 31, 1297 Founex</a>
                </p>
            </div>

            <div>
                <p class="visit__label">{{ __('footer.hours') }}</p>
                <p class="visit__value">
                    <span class="visit__line d-block">{{ __('footer.hours_week') }}</span>
                    <span class="visit__line d-block">{{ __('footer.hours_closed') }}</span>
                </p>
            </div>

            <div>
                <p class="visit__label">{{ ucfirst(__('nav.contact')) }}</p>
                <p class="visit__value">
                    <span class="visit__line d-block"><a href="tel:+41227761029">022 776 10 29</a></span>
                    <span class="visit__line d-block"><a href="mailto:aubergedefounex@bluewin.ch">aubergedefounex@bluewin.ch</a></span>
                </p>
            </div>
        </div>
    </div>
</section>
