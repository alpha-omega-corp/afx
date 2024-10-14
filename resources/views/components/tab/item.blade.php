<div
    x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
    :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
    role="tabpanel">

    @if(isset($information))
        <div class="tab-page-header">
                {{$information}}

            @if(isset($header))
                {{$header}}
            @endif
        </div>
    @endif


    <div class="tab-page-content">
        {{$slot}}
    </div>
</div>
