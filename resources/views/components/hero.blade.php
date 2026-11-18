@props([
    'image' => null,
    'title' => null,
    'lead' => null,
    'text' => null,
    'tall' => false,
    'priority' => false,
    'embers' => false,
    'rule' => false,
    'centered' => false,
    'emblem' => null,
    'preview' => null,
])

@php($quiet = $embers === 'quiet')
{{-- Every hero is a 300px photograph band with the page's name on it. A
     page with words of its own (every page but the home one) is the quieter
     kind: darker scrim, its words and its action under the band. --}}
@php($stage = filled($text))

{{-- The home page's hero is the other kind: taller, and the name, the lead
     and the actions all on the photograph, centred, rather than the name
     alone with the rest under the band. --}}
@php($centered = $centered && ! $stage)

<header @class([
    'hero',
    'hero--tall' => $tall,
    'hero--bare' => ! $image && ! $stage,
    'hero--media' => $image || $stage,
    'hero--stage' => $stage,
    'hero--rule' => $rule,
    'hero--quiet' => $quiet,
    'hero--cover' => $image,
    'hero--centered' => $centered,
])>
    {{-- The page's photograph fills the band behind the name, and
         scrolls away slower than the page (app.js,
         "Hero parallax"). Still a real <img>, so the browser can fetch it
         first as the largest thing on the page. --}}
    @if($image)
        <div class="hero__bg" data-parallax aria-hidden="true">
            <img
                class="hero__bg-img"
                src="{{ url($image) }}"
                alt=""
                {{ $priority ? 'fetchpriority=high' : 'loading=lazy' }}
                decoding="async"
            />
        </div>
        <div class="hero__scrim" aria-hidden="true"></div>
    @endif

    @if($embers)
        {{-- An auberge is a hearth: a warm light from above, burning down
             to the page colour before the cards arrive. --}}
        <div class="hero__hearth" aria-hidden="true"></div>
        <div class="hero__fade" aria-hidden="true"></div>
    @endif

    {{-- The band holds the name and nothing else: at a fixed 300px under
         the bar there is room for a title, not for a paragraph and buttons
         as well. Everything else follows on the page, under the photograph. --}}
    <div class="hero__inner">
        <div class="container">
            @if($centered)
                <div class="hero__text">
                    @isset($eyebrow)
                        <div class="hero__eyebrow">{{ $eyebrow }}</div>
                    @endisset

                    <h1 class="hero__title">{{ $title }}</h1>

                    @if($lead)
                        <p class="hero__lead">{{ $lead }}</p>
                    @endif

                    @isset($actions)
                        <div class="hero__actions">{{ $actions }}</div>
                    @endisset
                </div>
            @else
                <h1 @class(['hero__title', 'hero-page__title' => $stage])>{{ $title }}</h1>
            @endif
        </div>
    </div>
</header>

@if(! $centered && (isset($eyebrow) || $lead || $stage || isset($actions)))
    <div @class(['hero-body', 'hero-body--stage' => $stage])>
        <div class="container">
            @if($stage)
                {{-- An inner page: its words and its one action, straight
                     under the photograph, no card. --}}
                {{-- The page's ember-line drawing stands to the right of its
                     words, in the room their 52ch measure leaves. A drawing
                     beside the words, never on the photograph. --}}
                <div @class(['hero-page__row' => $emblem])>
                <div class="hero-page">
                    @isset($eyebrow)
                        <div class="hero__eyebrow">{{ $eyebrow }}</div>
                    @endisset

                    {{-- French puts a space before : ; ? ! — a thin
                         non-breaking one, so the mark never starts a
                         line on its own. --}}
                    {{-- With a preview, the words under the band are the
                         page's first sentence and an icon after it; the
                         whole text, and the page in miniature, open in the
                         modal x-page pushes (x-page-preview). --}}
                    @php($words = trim((string) $text))
                    @if($preview && preg_match('/^.+?[.!?…](?=\s|$)/us', $words, $first))
                        @php($words = $first[0])
                    @endif

                    <p @class(['hero-page__text', 'hero-page__text--short' => $preview])>{{ preg_replace('/ ([:;?!])/u', "\u{202F}$1", $words) }}@if($preview)&nbsp;<button
                            type="button"
                            class="hero-page__more"
                            data-bs-toggle="modal"
                            data-bs-target="#{{ $preview }}"
                            aria-haspopup="dialog"
                            aria-label="{{ __('app.preview_open') }}"
                            title="{{ __('app.preview_open') }}"
                        >@svg('heroicon-o-arrows-pointing-out', ['aria-hidden' => 'true'])</button>@endif</p>

                    @isset($actions)
                        <div class="hero__actions hero-page__actions">{{ $actions }}</div>
                    @endisset
                </div>

                @if($emblem)
                    <div class="hero-page__emblem">
                        <x-dynamic-component :component="'illustration.' . $emblem"/>
                    </div>
                @endif
                </div>
            @else
                <div class="hero__text">
                    @isset($eyebrow)
                        <div class="hero__eyebrow">{{ $eyebrow }}</div>
                    @endisset

                    @if($lead)
                        <p class="hero__lead">{{ $lead }}</p>
                    @endif

                    @if(isset($actions))
                        <div class="hero__actions">{{ $actions }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endif
