<footer class="app-footer">
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <div class="app-footer__section">
                    <h3>{{__('footer.sitemap')}}</h3>

                    <li>
                        <a href="{{route(__('route.home'))}}">{{__('nav.home')}}</a>
                    </li>
                    <li>
                        <a href="{{route(__('route.menu'))}}">{{__('nav.menu')}}</a>
                    </li>
                    <li>
                        <a href="{{route(__('route.restaurant'))}}">{{__('nav.restaurant')}}</a>
                    </li>
                    <li>
                        <a href="{{route(__('route.hotel'))}}">{{__('nav.hotel')}}</a>
                    </li>
                    <li>
                        <a href="{{route(__('route.contact'))}}">{{__('nav.contact')}}</a>
                    </li>
                </div>
            </div>

            <div class="col-md-4">
                <div class="app-footer__section">
                    <h3>{{__('footer.address')}}</h3>
                    <p>Grand'Rue 31 1297 Founex</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="app-footer__section">
                    <h3>{{ucfirst(__('nav.contact'))}}</h3>

                    <div class="d-flex flex-column gap-2">
                        <a href="tel:022 776 10 29">022 776 10 29</a>
                        <a href="mailto:aubergedefounex@bluewin.ch">aubergedefounex@bluewin.ch</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</footer>
