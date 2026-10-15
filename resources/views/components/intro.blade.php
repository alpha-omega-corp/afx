@props(['text', 'eyebrow' => null, 'facts' => true])

{{-- A page's short paragraph in a panel of its own: the words on the left,
     the drawing (`art` slot) and the practical facts on the right. The facts
     are the house's real ones — hours from App\Support\Opening, the numbers
     and the address — never anything the copy would have to invent. --}}
<section class="intro">
    <div class="container">
        <div class="intro__panel">
            <div class="intro__body">
                @if($eyebrow)
                    <p class="intro__eyebrow">{{ $eyebrow }}</p>
                @endif

                {{-- French puts a space before : ; ? ! — a thin non-breaking one, so
                     the mark never starts a line on its own. --}}
                <p class="intro__text">{{ preg_replace('/ ([:;?!])/u', "\u{202F}$1", $text) }}</p>

                @isset($actions)
                    <div class="intro__actions">{{ $actions }}</div>
                @endisset
            </div>

            <aside @class(['intro__aside', 'intro__aside--art-only' => ! $facts]) @if($facts) aria-label="{{ __('app.practical') }}" @endif>
                @isset($art)
                    <div class="intro__art">{{ $art }}</div>
                @endisset

                @if($facts)
                    <dl class="intro__facts">
                        <div class="intro__fact">
                            @svg('heroicon-o-clock')
                            <dt>{{ __('footer.hours') }}</dt>
                            <dd>
                                @foreach(App\Support\Opening::schedule() as $line)
                                    {{ $line }}@if(! $loop->last)<br>@endif
                                @endforeach
                            </dd>
                        </div>

                        <div class="intro__fact">
                            @svg('heroicon-o-phone')
                            <dt>{{ __('app.reservations') }}</dt>
                            <dd>
                                <a href="tel:+41227761029">022 776 10 29</a>
                                · <a href="https://wa.me/+41786857845" target="_blank" rel="noopener">WhatsApp</a>
                            </dd>
                        </div>

                        <div class="intro__fact">
                            @svg('heroicon-o-map-pin')
                            <dt>{{ __('footer.address') }}</dt>
                            <dd>
                                <a href="{{ route(__('route.contact')) }}">Grand'Rue 31, 1297 Founex</a>
                            </dd>
                        </div>
                    </dl>
                @endif
            </aside>
        </div>
    </div>
</section>
