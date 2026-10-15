{{-- $attributes rather than @class: an x-show, a @click or a class written at
     the call site has to reach the button itself. data-bs-toggle and
     data-bs-target do not move — the whole back office opens its modals
     through them. --}}
<button
    type="button"
    {{ $attributes->class(['admin-action', 'admin-action--' . $intent, 'admin-action--compact' => $compact]) }}
    data-bs-toggle="modal"
    data-bs-target="#{{ $id }}"
    @if($compact) aria-label="{{ $label }}" title="{{ $label }}" @endif
>
    {{-- No aria-hidden here: heroicons already carry aria-hidden="true" on the
         <svg>, and blade-icons does not de-duplicate attributes — passing it
         again writes it into the tag twice, which is invalid HTML for no
         accessibility gain at all. --}}
    @svg($icon->value, 'admin-action__icon', ['focusable' => 'false'])

    @unless($compact)
        <span class="admin-action__label">{{ $label }}</span>
    @endunless
</button>
