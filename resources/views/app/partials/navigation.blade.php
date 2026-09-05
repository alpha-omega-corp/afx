<li @class(['active-item' => Request::is('/') || Request::is('en')])>
    <a href="{{ route(__('route.home')) }}"
       @if(Request::is('/') || Request::is('en')) aria-current="page" @endif>{{ __('nav.home') }}</a>
</li>
<li @class(['active-item' => Request::is('la-carte') || Request::is('en/menu')])>
    <a href="{{ route(__('route.menu')) }}"
       @if(Request::is('la-carte') || Request::is('en/menu')) aria-current="page" @endif>{{ __('nav.menu') }}</a>
</li>
<li @class(['active-item' => Request::is('restaurant') || Request::is('en/restaurant')])>
    <a href="{{ route(__('route.restaurant')) }}"
       @if(Request::is('restaurant') || Request::is('en/restaurant')) aria-current="page" @endif>{{ __('nav.restaurant') }}</a>
</li>
<li @class(['active-item' => Request::is('hotel') || Request::is('en/hotel')])>
    <a href="{{ route(__('route.hotel')) }}"
       @if(Request::is('hotel') || Request::is('en/hotel')) aria-current="page" @endif>{{ __('nav.hotel') }}</a>
</li>
<li @class(['active-item' => Request::is('contact') || Request::is('en/contact')])>
    <a href="{{ route(__('route.contact')) }}"
       @if(Request::is('contact') || Request::is('en/contact')) aria-current="page" @endif>{{ __('nav.contact') }}</a>
</li>
