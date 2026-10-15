{{-- The French / English pair, in that order: the tab at position 1 pairs
     with the panel at position 1. `justify="end"` is gone — the strip is a
     full-width segmented control now, and the attribute was being dropped
     on the floor anyway (tab.index never rendered $attributes). --}}
<div class="mt-4">
    {{-- The strip is named, because several identical ones can live in the
         same document — one per edit dialog — and "tab list, FR, selected"
         alone does not say a tab list of what. --}}
    <x-tab :label="__('nav.language')"
        :items="[
            strtoupper(Lang::FR->name),
            strtoupper(Lang::EN->name)
        ]"
    >
        <x-tab.item>
            {{ $french }}
        </x-tab.item>

        <x-tab.item>
            {{ $english }}
        </x-tab.item>
    </x-tab>
</div>
