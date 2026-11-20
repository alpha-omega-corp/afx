@props(['page' => null, 'title' => null, 'lead' => null, 'tall' => false, 'embers' => 'quiet', 'rule' => true, 'intro' => true, 'centered' => false, 'emblem' => null])

{{-- Every page opens the same way: its record's photograph as a 300px band
     with its name on it, its words under it, then whatever the page itself
     is about. The hours and the address live
     in their own band further down the home page (x-visit) and in the footer. All of it reads from the Page row edited under
     Administration → Pages, so a photograph or a paragraph changed there
     lands in the same place on every page. Without words (the home page, or
     a record not yet written) the hero is the name beside the photograph. --}}
@php($text = $intro ? $page?->locale?->content : null)

<div class="app-page">
    <x-hero
        :image="$page?->image"
        :title="$title"
        :lead="$lead"
        :text="$text"
        :tall="$tall && ! filled($text)"
        :embers="$embers"
        :rule="$rule"
        :centered="$centered"
        :emblem="$emblem"
        :preview="filled($text) ? 'page-preview' : null"
        :priority="true"
    >
        @isset($eyebrow)
            <x-slot:eyebrow>{{ $eyebrow }}</x-slot:eyebrow>
        @endisset
        {{-- The page's call to action sits under its words when it has
             words; otherwise under the name, as the hero's own buttons. --}}
        @if(filled($text) && isset($cta))
            <x-slot:actions>{{ $cta }}</x-slot:actions>
        @elseif(isset($actions))
            <x-slot:actions>{{ $actions }}</x-slot:actions>
        @endif
        @isset($special)
            <x-slot:special>{{ $special }}</x-slot:special>
        @endisset
    </x-hero>

    {{-- The page in miniature, opened from the icon after its first
         sentence: the photograph, all of its words, what the page holds
         (its `preview` slot) and the same call to action. --}}
    @if(filled($text))
        @push('modals')
            <x-page-preview id="page-preview" :image="$page?->image" :title="$title" :text="$text" :emblem="$emblem">
                {{ $preview ?? '' }}

                @isset($cta)
                    <x-slot:actions>{{ $cta }}</x-slot:actions>
                @endisset
            </x-page-preview>
        @endpush
    @endif

    <div class="app-page__content">
        {{ $slot }}
    </div>
</div>
