<div class="input-group mb-3">
    @if($icon)
        <div class="input-group-text">
            @svg($icon->value, 'w-6 h-6')
        </div>
    @endif

    <div class="form-floating">
        {{-- placeholder is required for Bootstrap's floating labels to move --}}
        @if($model)
            {{-- Bound inputs are cloned once per repeater row, so the id has to
                 carry the row index to stay unique. --}}
            <input {{ $required ? 'required' : '' }}
                   type="{{ $type }}"
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
                   value="{{ $value }}"
                   name="{{ $name }}"
                   class="form-control"
                   placeholder=" "
                   id="{{ $id }}">
            <label for="{{ $id }}">{{ $label }}</label>
        @endif
    </div>
</div>
