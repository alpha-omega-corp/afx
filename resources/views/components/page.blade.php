@props(['image' => null, 'title' => null, 'lead' => null, 'tall' => false, 'band' => true, 'embers' => false])

<div class="app-page">
    <x-hero :image="$image" :title="$title" :lead="$lead" :tall="$tall" :embers="$embers" :priority="true">
        @isset($actions)
            <x-slot:actions>{{ $actions }}</x-slot:actions>
        @endisset
    </x-hero>

    <div class="app-page__content">
        {{ $slot }}
    </div>

    @if($band)
        <x-visit-band/>
    @endif
</div>
