{{-- The addon and the field are one object: the surface, the border and the
     radius all live on .input-group, and the two children inside it are
     transparent and borderless. That is what lets :focus-within light the
     icon and the field together instead of stopping at the seam between
     them — see the note at the head of _input.scss.

     `w-6 h-6` were Tailwind class names in a build that has no Tailwind, so
     they sized nothing at all; the icon is sized in the stylesheet instead.
     No a11y attributes are passed either: heroicons already ship
     aria-hidden="true" on the <svg>, and blade-icons does not de-duplicate
     attributes — passing it again writes it into the tag twice. --}}
<div class="input-group mb-3">
    @if($icon)
        <div class="input-group-text">
            @svg($icon->value)
        </div>
    @endif

    <div class="form-floating">
        {{-- placeholder is required for Bootstrap's floating labels to move --}}
        @if($model)
            {{-- Bound inputs are cloned once per repeater row, so the id has to
                 carry the row index to stay unique. --}}
            <input {{ $required ? 'required' : '' }}
                   type="{{ $type }}"
                   @if($step) step="{{ $step }}" @endif
                   value="{{ $value }}"
                   name="{{ $name }}"
                   x-model="{{ $model }}"
                   class="form-control"
                   placeholder=" "
                   :id="`{{ $id }}-${index}`">
            <label :for="`{{ $id }}-${index}`">{{ $label }}</label>
        @else
            <input {{ $required ? 'required' : '' }}
                   type="{{ $type }}"
                   @if($step) step="{{ $step }}" @endif
                   value="{{ $value }}"
                   name="{{ $name }}"
                   class="form-control"
                   placeholder=" "
                   id="{{ $id }}">
            <label for="{{ $id }}">{{ $label }}</label>
        @endif
    </div>
</div>
