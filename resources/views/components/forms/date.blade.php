<div class="form-floating mb-3">
    <input {{ $required ? 'required' : '' }}
           type="{{ $type }}"
           class="form-control"
           id="{{ $id }}"
           name="{{ $name }}"
           value="{{ $value }}"
           placeholder=" "
           {{ $attributes }}>

    <label for="{{ $id }}">{{ $label }}</label>
</div>
