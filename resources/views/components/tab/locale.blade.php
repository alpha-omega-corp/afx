<div class="mt-4">
    <x-tab :items="[
            strtoupper(Lang::FR->name),
            strtoupper(Lang::EN->name)
        ]"
           justify="end"
    >
        <x-tab.item>
            {{$french}}
        </x-tab.item>

        <x-tab.item>
            {{$english}}
        </x-tab.item>
    </x-tab>
</div>
