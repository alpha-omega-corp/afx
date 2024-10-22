<x-card :title="__('app.gallery')">

    <div x-data="images('gallery-photo')" class="app-gallery">
        <div class="app-gallery__actions">
            <x-modal.open :name="Modal::ADMIN_GALLERY" :action="Action::CREATE" :icon="Icon::CREATE"/>

            <template x-if="selected.length > 0">
                <x-modal.open :name="Modal::ADMIN_GALLERY" :action="Action::DELETE" :icon="Icon::DELETE"/>
            </template>
        </div>

        <x-modal.index
            :name="Modal::ADMIN_GALLERY"
            :action="Action::CREATE"
            title="gallery"
            :route="route('gallery.store', $gallery)"
        >
            <input type="file" name="items[]" multiple id="gallery-photo" class="p-4" x-on:change="upload">
            <div class="app-gallery__container"></div>
        </x-modal.index>

        <x-modal.index
            :title="__('action.delete')"
            :name="Modal::ADMIN_GALLERY"
            :action="Action::DELETE"
        >
            <p class="p-4">Delete <span x-text="selected.length"></span> images ?</p>

            <x-slot:submit>
                <button type="button" class="btn btn-danger text-white" @click="remove()">Submit</button>
            </x-slot:submit>
        </x-modal.index>

        <div class="app-gallery__container">
            @foreach($gallery->items as $item)
                <div class="app-gallery__image">
                    <img src="{{asset($item->image)}}" alt="" @click="select('{{$item->id}}')">

                    <div class="app-gallery__image-overlay">
                        <div class="form-check">
                            <input class="form-check-input gallery-select" id="select-{{$item->id}}" type="checkbox" value="">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</x-card>
