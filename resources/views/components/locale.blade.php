<div class="app-locale">
    <a href="{{route('locale', ['locale' => 'fr'])}}"
        @class(['app-locale-active' => app()->getLocale() === \App\Enums\Language::FR->value])>
        <img src="{{Vite::image('french.png')}}" alt="French">
    </a>

    <a href="{{route('locale', ['locale' => 'en'])}}"
        @class(['app-locale-active' => app()->getLocale() === \App\Enums\Language::EN->value])>
        <img src="{{Vite::image('english.png')}}" alt="French">
    </a>

</div>
