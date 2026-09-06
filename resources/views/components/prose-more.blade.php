@props(['text' => null, 'limit' => 200])

@php
    $full = trim((string) $text);

    // Cut at the last word inside the limit, not mid-word, and measure in
    // characters rather than bytes — "à", "ê" and "'" are all over this copy.
    $long = mb_strlen($full) > $limit;
    $short = $long
        ? rtrim(preg_replace('/\s+\S*$/u', '', mb_substr($full, 0, $limit)), " \t\n\r\0\x0B,;:")
        : $full;
@endphp

@if($full === '')
@elseif(! $long)
    <p class="prose">{{ $full }}</p>
@else
    {{-- A native disclosure rather than a script: it opens with no JS, has no
         flash of the wrong state on load, and carries its own semantics for a
         screen reader. The short copy lives in the summary and is hidden once
         the full paragraph is showing, so neither is read twice. --}}
    <details class="prose-more">
        <summary class="prose-more__summary">
            <span class="prose prose-more__short">{{ $short }}…</span>

            <span class="prose-more__toggle">
                <span class="prose-more__more">{{ __('app.show_more') }}</span>
                <span class="prose-more__less">{{ __('app.show_less') }}</span>
            </span>
        </summary>

        <p class="prose prose-more__full">{{ $full }}</p>
    </details>
@endif
