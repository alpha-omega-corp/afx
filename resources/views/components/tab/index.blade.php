{{-- A tab strip that is now shaped like the segmented control it always
     was, and announced like the tabs it always was.

     What changed in the markup, and why:

     - the strip was a `<ul role="list">` of `<li>` holding `<a role="button">`.
       A list is not a tablist and a button is not a tab: a screen reader
       read out "list, 2 items, button, FR" and never once said that FR was
       the selected one of a set. It is now `role="tablist"` holding real
       `role="tab"` buttons, each naming the panel it opens (aria-controls),
       and each panel naming its tab back (aria-labelledby, in tab.item).
     - `<button type="button">` and not a bare `<button>`: this strip renders
       inside the `<form>` of a modal, and a bare button submits it.
     - `whichChild()` is untouched. It is simply called with `$el` — the
       button — now that the `<li>` it used to have to step over is gone.
     - `x-id` gained a second name so the panels can have real ids to be
       pointed at.

     What did NOT change: selectedId / select / isSelected / whichChild, the
     six keyboard handlers and their $focus calls, @mousedown.prevent,
     @focus, :tabindex and :aria-selected. The roving tabindex and the
     arrow-key behaviour are exactly what they were. --}}
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
    x-id="['tab', 'tabpanel']"
    class="tab"
>
    {{-- --tab-count is written server side: the sliding thumb is one tab
         wide and travels in whole multiples of itself, so the stylesheet
         needs to know how many there are. Nothing measures anything. --}}
    <div
        x-ref="list"
        @keydown.right.prevent.stop="$focus.wrap().next()"
        @keydown.home.prevent.stop="$focus.first()"
        @keydown.page-up.prevent.stop="$focus.first()"
        @keydown.left.prevent.stop="$focus.wrap().prev()"
        @keydown.end.prevent.stop="$focus.last()"
        @keydown.page-down.prevent.stop="$focus.last()"
        role="tablist"
        @if($label) aria-label="{{ $label }}" @endif
        class="tab__nav"
        style="--tab-count: {{ count($items) }}"
    >
        @foreach($items as $key => $tab)
            <button
                type="button"
                :id="$id('tab', whichChild($el, $refs.list))"
                :aria-controls="$id('tabpanel', whichChild($el, $refs.list))"
                @click="select($el.id)"
                @mousedown.prevent
                @focus="select($el.id)"
                :tabindex="isSelected($el.id) ? 0 : -1"
                :aria-selected="isSelected($el.id)"
                role="tab"
                class="tab__link"
            >
                <span class="tab__label">{{ $tab }}</span>
            </button>
        @endforeach

        {{-- The ground that slides. aria-hidden because a tablist may own
             nothing but tabs, and this is scenery. Last in the DOM, so it
             cannot shift the indices whichChild() counts. --}}
        <span class="tab__thumb" aria-hidden="true"></span>
    </div>

    <div class="tab__panels">
        {{ $slot }}
    </div>
</div>
