{{-- The picker, the preview and the input are wired to each other through
     Alpine refs rather than through element ids. Ids are global: this field is
     rendered once per page row, always under the same name, so every lookup by
     id found the first row's input and the row being edited submitted nothing.
     A ref is scoped to its own component, which is what this always meant. --}}
<div class="mb-3 w-100" x-data="{ preview: @js($file ? asset($file) : null) }">
    {{-- Nothing chosen yet: the whole block is the target. --}}
    <button type="button" class="file-upload" x-show="!preview" @click="$refs.input.click()">
        @svg('heroicon-o-cloud-arrow-down')
        <span>{{ $label }}</span>
    </button>

    {{-- Chosen, or already saved: the picture itself, with a way back to the
         picker over it. --}}
    <div class="file-preview" x-show="preview" x-cloak>
        <div class="file-label">
            <div class="preview-icon">
                @svg('heroicon-o-photo')
            </div>
            <div class="preview-label">{{ $label }}</div>
        </div>

        <button type="button" class="preview-edit" @click="$refs.input.click()" aria-label="{{ $label }}">
            @svg(Icon::EDIT->value)
        </button>

        <img class="image-preview" :src="preview" alt="">
    </div>

    <input
        type="file"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $id }}"
        class="d-none"
        accept="image/*"
        @if($multiple) multiple @endif
        x-ref="input"
        @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview"
    >
</div>
