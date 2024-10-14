@php use \Illuminate\Support\Facades\Request; @endphp

<div class="app-navigation">

    <div class="app-navigation__logo">
        <span>Auberge De</span>
        <span>Founex</span>
    </div>

    <div class="d-flex gap-4">
        <ul class="app-navigation__menu">
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
        </ul>

        <x-locale/>
    </div>
</div>

<x-modal.index
    :name="Modal::APP_LOGIN"
    :action="Action::CREATE"
    :title="__('app.login')"
    :route="route('auth.login')"
>

    <div class="p-4">
        <x-forms.input
            :icon="Icon::EMAIL"
            :label="__('form.email')"
            name="email"
            type="email"
        />

        <x-forms.input
            :icon="Icon::PASSWORD"
            :label="__('form.password')"
            name="password"
            type="password"
        />
    </div>

</x-modal.index>


