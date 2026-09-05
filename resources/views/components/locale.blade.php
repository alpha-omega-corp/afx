@php($current = app()->getLocale())

<div class="app-locale">
    <a href="{{ route('locale', ['locale' => 'fr']) }}"
       hreflang="fr"
       @class(['app-locale-active' => $current === \App\Enums\Language::FR->value])
       @if($current === \App\Enums\Language::FR->value) aria-current="true" @endif
    >FR</a>

    <span class="app-locale__sep" aria-hidden="true">/</span>

    <a href="{{ route('locale', ['locale' => 'en']) }}"
       hreflang="en"
       @class(['app-locale-active' => $current === \App\Enums\Language::EN->value])
       @if($current === \App\Enums\Language::EN->value) aria-current="true" @endif
    >EN</a>
</div>
