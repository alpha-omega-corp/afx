{{-- `Js::from`, never `json_encode` wrapped in quotes.

     The rows used to be handed over as a single-quoted JS string. The browser
     decodes &#039; back to an apostrophe before the JS parser ever sees it, so
     one dish called "Souris d'agneau" closed the string early, x-data threw a
     syntax error, Alpine never booted this component and the repeater rendered
     no rows at all — no existing dishes, and an Add button that did nothing.
     Half the carte is d'agneau, à l'ail, l'oignon.

     Js::from escapes apostrophes and quotes to ' and ", so nothing
     in a dish name can close the attribute or the literal, and the component
     receives real arrays rather than strings to parse. --}}
<div class="app-repeater" x-data="repeater({{ Js::from($items) }}, {{ Js::from($options()) }})">
    <h3 class="app-repeater__title">{{ $title }}</h3>

    <div class="app-repeater__items">
        <template x-for="(item, index) in values" :key="index">
            <div class="app-repeater__item">
                <div class="app-repeater__fields">
                    {{ $slot }}

                    <input type="hidden" name="{{ $idName }}" x-model="item.id"/>
                </div>

                {{-- .u-tap gives the 36px button a 44px hit area on a coarse
                     pointer without changing what is drawn; the row's 1rem of
                     padding is what keeps that halo clear of the row's edge.

                     aria-label is a plain attribute rather than an Alpine
                     binding around a single-quoted PHP string: the value is
                     static, and a translation containing an apostrophe would
                     have closed the literal — the exact failure the note at
                     the top of this file is about. --}}
                <button
                    type="button"
                    class="admin-action admin-action--danger admin-action--compact u-tap"
                    @click="remove(index)"
                    aria-label="{{ __('admin.action.delete') }}"
                    title="{{ __('admin.action.delete') }}"
                >
                    @svg(Icon::DELETE->value, 'admin-action__icon')
                </button>
            </div>
        </template>
    </div>

    {{-- Centred, at its natural width. It used to be w-100: a 700px button
         that grows 2% under the cursor overhangs its container by 7px a
         side and lands on its neighbour's border. --}}
    <div class="app-repeater__footer">
        <button type="button" class="admin-action admin-action--neutral" @click="add()">
            @svg(Icon::CREATE->value, 'admin-action__icon')
            <span class="admin-action__label">{{ __('admin.action.create') }}</span>
        </button>
    </div>
</div>
