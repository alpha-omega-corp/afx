<li @class(['active-item' => Request::is('/')])>
    <a
        href="{{route(__('route.home'))}}">
        {{__('nav.home')}}
    </a>
</li>
<li @class(['active-item' => Request::is('la-carte')])>
    <a
        href="{{route(__('route.menu'))}}">
        {{__('nav.menu')}}
    </a>
</li>
<li @class(['active-item' => Request::is('restaurant')])>
    <a
        href="{{route(__('route.restaurant'))}}">
        {{__('nav.restaurant')}}
    </a>
</li>
<li @class(['active-item' => Request::is('hotel')])>
    <a
        href="{{route(__('route.hotel'))}}">
        {{__('nav.hotel')}}
    </a>
</li>
<li @class(['active-item' => Request::is('contact')])>
    <a
        href="{{route(__('route.contact'))}}">
        {{__('nav.contact')}}
    </a>
</li>
<li @class(['active-item' => Request::is('admin/**') || Request::is('admin')])>
    @if(\Illuminate\Support\Facades\Auth::user())
        <a href="{{route('admin.home')}}">Admin</a>
    @else
        <x-modal.open :name="Modal::APP_LOGIN" :action="Action::CREATE" :title="__('app.login')"/>
    @endif
</li>

<x-locale/>
