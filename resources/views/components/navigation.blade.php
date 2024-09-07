<div class="app-navigation">

    <div class="app-navigation__logo">
        <span>Auberge De</span>
        <span>Founex</span>
    </div>

    <div class="d-flex gap-4">
        <ul class="app-navigation__menu">
            <li>
                <a href="{{route(__('route.home'))}}">{{__('nav.home')}}</a>
            </li>
            <li>
                <a href="{{route(__('route.about'))}}">{{__('nav.about')}}</a>
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
        </ul>

        <x-locale/>
    </div>
</div>
