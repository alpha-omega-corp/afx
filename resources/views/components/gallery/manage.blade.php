{{-- The Alpine scope stays above both the panel and the two modals: the
     delete dialog reads `selected` too. The modals inside are position:fixed
     and therefore not grid items, so the whole thing is one bento cell. --}}
<div x-data="gallery" class="bento-xl-12">
    {{-- flat: a gallery of forty photographs is several screens tall, and a
         panel taller than a viewport is the first thing the blur budget gives
         up — the ambient behind it is a low-frequency gradient that loses
         nothing by not being sampled. --}}
    <x-admin.panel :description="__('admin.gallery_description')" tone="flat">
        <x-slot:actions>
            {{-- Destructive action only exists once there is a selection.
                 x-show rather than a template x-if: Alpine inserts and removes
                 an x-if node without going through the transition engine, so
                 the chip would appear and vanish on a hard cut. It is anchored
                 to the right edge of the group, which is why it grows from
                 there — nothing already on screen moves when it arrives. --}}
            <button
                type="button"
                class="admin-action admin-action--danger"
                x-show="selected.length"
                x-cloak
                x-transition:enter="tr-enter tr-origin-end"
                x-transition:enter-start="tr-from-chip"
                x-transition:enter-end="tr-to"
                x-transition:leave="tr-leave tr-origin-end"
                x-transition:leave-start="tr-to"
                x-transition:leave-end="tr-from-chip"
                data-bs-toggle="modal"
                data-bs-target="#{{ \App\Helpers\ModalHelper::getId(Modal::ADMIN_GALLERY, Action::DELETE, null) }}"
            >
                {{-- aria-hidden is already on the heroicon itself and blade-icons
                     does not de-duplicate: passing it again writes it twice. --}}
                @svg(Icon::DELETE->value, 'admin-action__icon', ['focusable' => 'false'])
                <span class="admin-action__label u-tabular">
                    {{ __('admin.action.delete_images') }}
                    (<span x-text="selected.length"></span>)
                </span>
            </button>

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
                        {{-- The checkbox stays visible to the accessibility
                             tree — opacity only, never display:none or hidden.
                             It would lose focus, and :focus-within is the only
                             thing that shows a keyboard user what is selected.
                             The visually-hidden span is its name: without it
                             the box announces as "checkbox, not checked" and
                             nothing else. --}}
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

                            <span class="visually-hidden">
                                {{ __('admin.gallery.select', ['n' => $loop->iteration]) }}
                            </span>

                            <img
                                class="admin-gallery__image"
                                src="{{ asset($item->image) }}"
                                alt=""
                                loading="lazy"
                                decoding="async"
                            >

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

            {{-- --i staggers the entrance of each thumbnail; the cascade is
                 capped in the stylesheet, past a dozen it stops being a
                 cascade and starts being a wait. --}}
            <div class="admin-upload__previews" x-show="previews.length" x-cloak>
                <template x-for="(src, index) in previews" :key="src">
                    <img
                        class="admin-upload__preview"
                        :src="src"
                        :style="'--i: ' + index"
                        alt=""
                    >
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
            (<span class="u-tabular" x-text="selected.length"></span> <span>{{ __('admin.selected') }}</span>)
        </p>

        <x-slot:submit>
            <button type="button" class="btn btn-danger" @click="remove()">
                {{ __('admin.action.delete') }}
            </button>
        </x-slot:submit>
    </x-modal.index>
</div>
