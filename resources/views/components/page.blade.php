@props(['page' => null, 'title' => null, 'lead' => null, 'tall' => false, 'embers' => 'quiet', 'parallax' => false, 'rule' => true, 'intro' => true, 'facts' => true])

{{-- Every page opens the same way: its record's photograph and name in the
     hero, then its record's words in the intro panel, then whatever the page
     itself is about. Both read from the Page row edited under
     Administration → Pages, so a photograph or a paragraph changed there
     lands in the same place on every page. --}}
<div class="app-page">
    <x-hero :image="$page?->image" :title="$title" :lead="$lead" :tall="$tall" :embers="$embers" :parallax="$parallax" :rule="$rule" :priority="true">
        @isset($eyebrow)
            <x-slot:eyebrow>{{ $eyebrow }}</x-slot:eyebrow>
        @endisset
        @isset($actions)
            <x-slot:actions>{{ $actions }}</x-slot:actions>
        @endisset
    </x-hero>

    <div class="app-page__content">
        @if($intro && $page?->locale?->content)
            <x-intro :text="$page->locale->content" :facts="$facts">
                @isset($art)
                    <x-slot:art>{{ $art }}</x-slot:art>
                @endisset
                @isset($cta)
                    <x-slot:actions>{{ $cta }}</x-slot:actions>
                @endisset
            </x-intro>
        @endif

        {{ $slot }}
    </div>
</div>
