{{-- One panel. It finds its own number by counting its position among its
     siblings, which is why nothing may be inserted between the panels in
     tab.index — the tab at position n and the panel at position n are the
     same pair.

     The two panels are mutually exclusive blocks in normal flow, so they
     are sequenced rather than cross-faded: the one arriving waits out the
     one leaving (.delay-120), or the dialog's footer jumps by the
     difference in their heights halfway through the swap. --}}
<div
    class="tab__panel"
    role="tabpanel"
    :id="$id('tabpanel', whichChild($el, $el.parentElement))"
    :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
    x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
    x-cloak
    x-transition:enter="tr-enter delay-120"
    x-transition:enter-start="tr-from"
    x-transition:enter-end="tr-to"
    x-transition:leave="tr-leave"
    x-transition:leave-start="tr-to"
    x-transition:leave-end="tr-from"
>
    @if(isset($information))
        <div class="tab__panel-header">
            {{ $information }}

            @if(isset($header))
                {{ $header }}
            @endif
        </div>
    @endif

    <div class="tab__panel-body">
        {{ $slot }}
    </div>
</div>
