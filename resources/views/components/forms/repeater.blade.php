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

                <button
                    type="button"
                    class="admin-action admin-action--danger admin-action--compact"
                    @click="remove(index)"
                    :aria-label="'{{ __('admin.action.delete') }}'"
                    title="{{ __('admin.action.delete') }}"
                >
                    @svg(Icon::DELETE->value, 'admin-action__icon')
                </button>
            </div>
        </template>
    </div>

    <button type="button" class="admin-action admin-action--neutral w-100 justify-content-center" @click="add()">
        @svg(Icon::CREATE->value, 'admin-action__icon')
        <span class="admin-action__label">{{ __('admin.action.create') }}</span>
    </button>
</div>
