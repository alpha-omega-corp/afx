@php use \Illuminate\Support\Facades\Request; @endphp

<div class="app-navigation" x-data="{open: false}" >

    <div class="d-flex justify-content-between">
        <div class="app-navigation__logo">
            <span>Auberge De</span>
            <span>Founex</span>
        </div>

        <div @click="open = !open" class="app-navigation__icon">
            @svg('heroicon-o-bars-3')
        </div>
    </div>

    <ul class="app-navigation__menu-desktop">
        @include('app.partials.navigation')
    </ul>

    <div class="app-navigation__menu-mobile">
        <ul x-show="open" @click.outside="open = false">
            @include('app.partials.navigation')
        </ul>
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


