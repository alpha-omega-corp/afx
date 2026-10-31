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
{{-- A page with words of its own opens on one card, glass over the
     full-screen photograph: the name, the words and the way to act on them,
     the practical facts in a strip along the foot. --}}
@php($stage = filled($text))

<header @class([
    'hero',
    'hero--tall' => $tall,
    'hero--bare' => ! $image && ! $stage,
    'hero--media' => $image || $stage,
    'hero--stage' => $stage,
    'hero--rule' => $rule,
    'hero--quiet' => $quiet,
    'hero--cover' => $image,
])>
    {{-- The page's photograph fills the whole first screen behind
         everything else, and scrolls away slower than the page (app.js,
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

    <div class="hero__inner">
        @if($stage)
            <div class="container">
                <div @class(['hero-stage', 'hero-stage--facts' => $facts])>
                    <div class="hero-stage__body">
                        @isset($art)
                            <div class="hero-stage__art">{{ $art }}</div>
                        @endisset

                        @isset($eyebrow)
                            <div class="hero__eyebrow">{{ $eyebrow }}</div>
                        @endisset

                        <h1 class="hero__title hero-stage__title">{{ $title }}</h1>

                        {{-- French puts a space before : ; ? ! — a thin
                             non-breaking one, so the mark never starts a
                             line on its own. --}}
                        <p class="hero-stage__text">{{ preg_replace('/ ([:;?!])/u', "\u{202F}$1", $text) }}</p>

                        @isset($actions)
                            <div class="hero__actions hero-stage__actions">{{ $actions }}</div>
                        @endisset
                    </div>


                    {{-- The house's real facts — hours from App\Support\Opening,
                         the numbers and the address — never anything the copy
                         would have to invent. --}}
                    @if($facts)
                        <dl class="hero-stage__facts" aria-label="{{ __('app.practical') }}">
                            <div class="hero-stage__fact">
                                @svg('heroicon-o-clock')
                                <dt>{{ __('footer.hours') }}</dt>
                                <dd>
                                    @foreach(App\Support\Opening::schedule() as $line)
                                        <span>{{ $line }}</span>
                                    @endforeach
                                </dd>
                            </div>

                            <div class="hero-stage__fact">
                                @svg('heroicon-o-phone')
                                <dt>{{ __('app.reservations') }}</dt>
                                <dd>
                                    <a href="tel:+41227761029">022 776 10 29</a>
                                    · <a href="https://wa.me/+41786857845" target="_blank" rel="noopener">WhatsApp</a>
                                </dd>
                            </div>

                            <div class="hero-stage__fact">
                                @svg('heroicon-o-map-pin')
                                <dt>{{ __('footer.address') }}</dt>
                                <dd>
                                    <a href="{{ route(__('route.contact')) }}">Grand'Rue 31, 1297 Founex</a>
                                </dd>
                            </div>
                        </dl>
                    @endif
                </div>
            </div>
        @else
            <div class="container hero__grid">
                <div class="hero__text">
                    @isset($eyebrow)
                        <div class="hero__eyebrow">{{ $eyebrow }}</div>
                    @endisset

                    <h1 class="hero__title">{{ $title }}</h1>

                    @if($lead)
                        <p class="hero__lead">{{ $lead }}</p>
                    @endif

                    @if(isset($actions))
                        <div class="hero__actions">{{ $actions }}</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</header>
