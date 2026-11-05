@props([
    'image' => null,
    'title' => null,
    'lead' => null,
    'text' => null,
    'facts' => false,
    'tall' => false,
    'priority' => false,
    'embers' => false,
    'rule' => false,
])

@php($quiet = $embers === 'quiet')
{{-- Every hero is a 300px photograph band with the page's name on it. A
     page with words of its own (every page but the home one) is the quieter
     kind: darker scrim, its words and its action under the band. --}}
@php($stage = filled($text))

{{-- The house's real facts — hours from App\Support\Opening, the numbers and
     the address, never anything the copy would have to invent — under the
     home page's lead and actions. The inner pages' quieter
     heroes leave them to the footer. --}}
@php($practical = $facts && ! $stage && $image)

<header @class([
    'hero',
    'hero--tall' => $tall,
    'hero--bare' => ! $image && ! $stage,
    'hero--media' => $image || $stage,
    'hero--stage' => $stage,
    'hero--rule' => $rule,
    'hero--quiet' => $quiet,
    'hero--cover' => $image,
    'hero--facts' => $practical,
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
            <h1 @class(['hero__title', 'hero-page__title' => $stage])>{{ $title }}</h1>
        </div>
    </div>
</header>

@if(isset($eyebrow) || $lead || $stage || isset($actions) || $practical)
    <div @class(['hero-body', 'hero-body--stage' => $stage])>
        <div class="container">
            @if($stage)
                {{-- An inner page: its words and its one action, straight
                     under the photograph, no card. --}}
                <div class="hero-page">
                    @isset($eyebrow)
                        <div class="hero__eyebrow">{{ $eyebrow }}</div>
                    @endisset

                    {{-- French puts a space before : ; ? ! — a thin
                         non-breaking one, so the mark never starts a
                         line on its own. --}}
                    <p class="hero-page__text">{{ preg_replace('/ ([:;?!])/u', "\u{202F}$1", $text) }}</p>

                    @isset($actions)
                        <div class="hero__actions hero-page__actions">{{ $actions }}</div>
                    @endisset
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
            @if($practical)
                <dl class="hero__facts" aria-label="{{ __('app.practical') }}">
                    <div class="hero__fact">
                        @svg('heroicon-o-clock')
                        <dt>{{ __('footer.hours') }}</dt>
                        <dd>
                            {{-- A range is one thing: "11h30 – 14h00" never
                                 breaks at its dash (a no-break space is not
                                 enough, the dash itself is a break point). --}}
                            @foreach(App\Support\Opening::schedule() as $line)
                                <span>{!! preg_replace('/(\S+) – (\S+)/u', '<span class="text-nowrap">$1 – $2</span>', e($line)) !!}</span>
                            @endforeach
                        </dd>
                    </div>

                    <div class="hero__fact">
                        @svg('heroicon-o-phone')
                        <dt>{{ __('app.reservations') }}</dt>
                        <dd>
                            <a href="tel:+41227761029">022&nbsp;776&nbsp;10&nbsp;29</a>
                            · <a href="https://wa.me/+41786857845" target="_blank" rel="noopener">WhatsApp</a>
                        </dd>
                    </div>

                    <div class="hero__fact">
                        @svg('heroicon-o-map-pin')
                        <dt>{{ __('footer.address') }}</dt>
                        <dd>
                            <a href="{{ route(__('route.contact')) }}">Grand'Rue&nbsp;31, 1297&nbsp;Founex</a>
                        </dd>
                    </div>
                </dl>
            @endif
        </div>
    </div>
@endif
