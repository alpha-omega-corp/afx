<div
    x-data="{
        selectedId: null,
        init() {
            this.$nextTick(() => this.select(this.$id('tab', 1)))
        },
        select(id) {
            this.selectedId = id
        },
        isSelected(id) {
            return this.selectedId === id
        },
        whichChild(el, parent) {
            return Array.from(parent.children).indexOf(el) + 1
        }
    }"
    x-id="['tab']"
    @class([
    'tab',
])>

    <!-- Tab List -->
    <ul
        x-ref="list"
        @keydown.right.prevent.stop="$focus.wrap().next()"
        @keydown.home.prevent.stop="$focus.first()"
        @keydown.page-up.prevent.stop="$focus.first()"
        @keydown.left.prevent.stop="$focus.wrap().prev()"
        @keydown.end.prevent.stop="$focus.last()"
        @keydown.page-down.prevent.stop="$focus.last()"
        role="list"
        class="tab-list-horizontal justify-content-end">

        @foreach($items as $key => $tab)
            <li class="tab-list-item">
                <a type="button"
                        :id="$id('tab', whichChild($el.parentElement, $refs.list))"
                        @click="select($el.id)"
                        @mousedown.prevent
                        @focus="select($el.id)"
                        :tabindex="isSelected($el.id) ? 0 : -1"
                        :aria-selected="isSelected($el.id)"
                        role="button"
                        :class="isSelected($el.id) ? 'text-primary fw-bold' : 'tab-button-inactive'"
                        class="tab-button btn"

                >
                    {{$tab}}
                </a>
            </li>
        @endforeach
    </ul>


    <div class="w-100">
        <!-- Panels -->
        <div role="tabpanel" class="tab-page">
            {{$slot}}
        </div>
    </div>
</div>
