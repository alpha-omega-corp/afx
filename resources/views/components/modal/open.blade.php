@if($icon)
    <button class="modal-open btn btn-{{$color}} text-white" data-bs-toggle="modal" data-bs-target="#{{$id}}">
        @svg($icon->value)
    </button>
@else
    <button type="button" class="btn btn-{{$color}} modal-open" data-bs-toggle="modal" data-bs-target="#{{$id}}">
        {{$title}}
    </button>
@endif
