<div class="app-repeater" x-data="repeater('{{ json_encode($items) }}')">
    <h3 class="app-repeater__title">{{ $title }}</h3>

    <div class="app-repeater__items">
        <template x-for="(item, index) in values" :key="index">
            <div class="app-repeater__item">
                <div class="app-repeater__fields">
                    {{ $slot }}

                    <input type="hidden" name="ids[]" x-model="item.id"/>
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
