<div x-data="gallery">
    <x-admin.panel :description="__('admin.gallery_description')">
        <x-slot:actions>
            {{-- Destructive action only exists once there is a selection. --}}
            <template x-if="selected.length">
                <button
                    type="button"
                    class="admin-action admin-action--danger"
                    data-bs-toggle="modal"
                    data-bs-target="#{{ \App\Helpers\ModalHelper::getId(Modal::ADMIN_GALLERY, Action::DELETE, null) }}"
                >
                    @svg(Icon::DELETE->value, 'admin-action__icon')
                    <span class="admin-action__label">
                        {{ __('admin.action.delete_images') }}
                        (<span x-text="selected.length"></span>)
                    </span>
                </button>
            </template>

            <x-admin.action
                :name="Modal::ADMIN_GALLERY"
                :action="Action::CREATE"
                :label="__('admin.action.add_images')"
            />
        </x-slot:actions>

        @if($gallery->items->isEmpty())
            <p class="admin-empty">{{ __('admin.empty.gallery') }}</p>
        @else
            <ul class="admin-gallery">
                @foreach($gallery->items as $item)
                    <li>
                        <label
                            class="admin-gallery__item"
                            :class="{ 'is-selected': isSelected({{ $item->id }}) }"
                        >
                            <input
                                type="checkbox"
                                class="admin-gallery__checkbox"
                                value="{{ $item->id }}"
                                :checked="isSelected({{ $item->id }})"
                                @change="toggle({{ $item->id }})"
                            >

                            <img class="admin-gallery__image" src="{{ asset($item->image) }}" alt="">

                            <span class="admin-gallery__mark" aria-hidden="true">
                                @svg('heroicon-s-check')
                            </span>
                        </label>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.panel>

    <x-modal.index
        :name="Modal::ADMIN_GALLERY"
        :action="Action::CREATE"
        :title="__('admin.action.add_images')"
        :route="route('gallery.store', $gallery)"
    >
        <div x-data="upload">
            <input
                type="file"
                name="items[]"
                multiple
                accept="image/*"
                class="form-control"
                @change="select($event)"
            >

            <div class="admin-upload__previews" x-show="previews.length" x-cloak>
                <template x-for="src in previews" :key="src">
                    <img class="admin-upload__preview" :src="src" alt="">
                </template>
            </div>
        </div>
    </x-modal.index>

    <x-modal.index
        :name="Modal::ADMIN_GALLERY"
        :action="Action::DELETE"
        :title="__('admin.action.delete_images')"
    >
        <p class="admin-confirm">
            {{ __('admin.confirm.delete_images') }}
            (<span x-text="selected.length"></span> <span>{{ __('admin.selected') }}</span>)
        </p>

        <x-slot:submit>
            <button type="button" class="btn btn-danger" @click="remove()">
                {{ __('admin.action.delete') }}
            </button>
        </x-slot:submit>
    </x-modal.index>
</div>
