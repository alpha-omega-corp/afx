{{-- Open or closed this minute, and the next change: the week on Founex
     time, with the holidays and the manual switch on top
     (App\Support\Opening::status). Worked out on every request; nothing
     caches the page. --}}
@php($state = App\Support\Opening::status())

<span {{ $attributes->class(['open-status', 'is-open' => $state['open']]) }}>
    <span class="open-status__dot" aria-hidden="true"></span>
    <strong class="open-status__word">{{ $state['open'] ? __('app.open_now') : __('app.closed_now') }}</strong>
    @if($state['detail'])
        <span class="open-status__detail">{{ $state['detail'] }}</span>
    @endif
</span>
