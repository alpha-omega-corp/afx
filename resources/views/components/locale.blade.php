@php($current = app()->getLocale())
@php($route = Route::currentRouteName() ?? '')

<nav class="app-locale" aria-label="{{ __('nav.language') }}">
    @foreach(\App\Enums\Language::cases() as $lang)
        <a
            {{-- Straight to the same page in that language; the locale
                 redirect is only for pages outside the guest site. --}}
            href="{{ str_starts_with($route, 'guest.')
                ? route(str_replace(['guest.fr.', 'guest.en.'], "guest.{$lang->value}.", $route))
                : route('locale', ['locale' => $lang->value]) }}"
            hreflang="{{ $lang->value }}"
            @class(['app-locale__item', 'is-current' => $current === $lang->value])
            @if($current === $lang->value) aria-current="true" @endif
        >{{ strtoupper($lang->name) }}</a>
    @endforeach
</nav>
