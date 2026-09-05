<button
    type="button"
    @class(['admin-action', 'admin-action--' . $intent, 'admin-action--compact' => $compact])
    data-bs-toggle="modal"
    data-bs-target="#{{ $id }}"
    @if($compact) aria-label="{{ $label }}" title="{{ $label }}" @endif
>
    @svg($icon->value, 'admin-action__icon')

    @unless($compact)
        <span class="admin-action__label">{{ $label }}</span>
    @endunless
</button>
