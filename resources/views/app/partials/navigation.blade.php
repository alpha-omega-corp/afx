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

@auth
    <li class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('admin.home') }}">{{ __('nav.home') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.menu') }}">{{ __('nav.menu') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.restaurant') }}">{{ __('nav.restaurant') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.hotel') }}">{{ __('nav.hotel') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.contact') }}">{{ __('nav.contact') }}</a></li>
            <li><a class="dropdown-item" href="{{ route('auth.logout') }}">{{ __('nav.logout') }}</a></li>
        </ul>
    </li>
@endauth
