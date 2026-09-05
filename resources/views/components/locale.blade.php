@php($current = app()->getLocale())

<nav class="app-locale" aria-label="{{ __('nav.language') }}">
    @foreach(\App\Enums\Language::cases() as $lang)
        <a
            href="{{ route('locale', ['locale' => $lang->value]) }}"
            hreflang="{{ $lang->value }}"
            @class(['app-locale__item', 'is-current' => $current === $lang->value])
            @if($current === $lang->value) aria-current="true" @endif
        >{{ strtoupper($lang->name) }}</a>
    @endforeach
</nav>
