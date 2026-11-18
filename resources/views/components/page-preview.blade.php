@props(['id', 'image' => null, 'title' => null, 'text' => null, 'emblem' => null])

{{-- The page in miniature: its photograph with its name on it, all of its
     words (the hero under the band keeps only the first sentence), what
     else the page holds, and its one action. Opened by the icon after that
     first sentence (x-hero), pushed to the end of <body> (layouts/guest) so
     no stacking context on the page can hold it under the backdrop. --}}
<div class="modal fade page-preview" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div @class(['page-preview__band', 'page-preview__band--bare' => ! $image])>
                @if($image)
                    <img class="page-preview__img" src="{{ url($image) }}" alt="" loading="lazy" decoding="async"/>
                    <div class="page-preview__scrim" aria-hidden="true"></div>
                @endif

                <h2 class="page-preview__title" id="{{ $id }}-title">{{ $title }}</h2>

                <button
                    type="button"
                    class="btn-close page-preview__close"
                    data-bs-dismiss="modal"
                    aria-label="{{ __('admin.action.cancel') }}"
                ></button>
            </div>

            <div class="modal-body page-preview__body">
                <div @class(['page-preview__row' => $emblem])>
                    <div class="page-preview__words">
                        @foreach(preg_split('/\R{2,}/u', trim((string) $text)) as $paragraph)
                            <p class="page-preview__text">{{ preg_replace('/ ([:;?!])/u', "\u{202F}$1", $paragraph) }}</p>
                        @endforeach
                    </div>

                    @if($emblem)
                        <div class="page-preview__emblem" aria-hidden="true">
                            <x-dynamic-component :component="'illustration.' . $emblem"/>
                        </div>
                    @endif
                </div>

                @if(trim((string) $slot) !== '')
                    <div class="page-preview__inside">
                        <div class="page-preview__label">{{ __('app.preview_inside') }}</div>
                        {{ $slot }}
                    </div>
                @endif
            </div>

            <div class="modal-footer page-preview__footer">
                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                    {{ __('app.preview_view') }}
                </button>

                @isset($actions)
                    {{ $actions }}
                @endisset
            </div>
        </div>
    </div>
</div>
