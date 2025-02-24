<li @class(['active-item' => Request::is('/') || Request::is('en')])>
    <a
        href="{{route(__('route.home'))}}">
        {{__('nav.home')}}
    </a>
</li>
<li @class(['active-item' => Request::is('la-carte') || Request::is('en/menu')])>
    <a
        href="{{route(__('route.menu'))}}">
        {{__('nav.menu')}}
    </a>
</li>
<li @class(['active-item' => Request::is('restaurant') || Request::is('en/restaurant')])>
    <a
        href="{{route(__('route.restaurant'))}}">
        {{__('nav.restaurant')}}
    </a>
</li>
<li @class(['active-item' => Request::is('hotel') || Request::is('en/hotel')])>
    <a
        href="{{route(__('route.hotel'))}}">
        {{__('nav.hotel')}}
    </a>
</li>
<li @class(['active-item' => Request::is('contact') || Request::is('en/contact')])>
    <a
        href="{{route(__('route.contact'))}}">
        {{__('nav.contact')}}
    </a>
</li>
<li>
    @if(\Illuminate\Support\Facades\Auth::user())

        <div class="dropdown">
            <a class="btn btn-primary text-white p-2 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Admin
            </a>

            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('admin.home')}}">{{__('nav.home')}}</a></li>
                <li><a class="dropdown-item" href="{{route('admin.menu')}}">{{__('nav.menu')}}</a></li>
                <li><a class="dropdown-item" href="{{route('admin.restaurant')}}">{{__('nav.restaurant')}}</a></li>
                <li><a class="dropdown-item" href="{{route('admin.hotel')}}">{{__('nav.hotel')}}</a></li>
                <li><a class="dropdown-item" href="{{route('admin.contact')}}">{{__('nav.contact')}}</a></li>
            </ul>
        </div>

    @else
        <x-modal.open :name="Modal::APP_LOGIN" :action="Action::CREATE" :title="__('app.login')"/>
    @endif
</li>

<x-locale/>
