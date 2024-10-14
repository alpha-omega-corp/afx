<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-6 text-uppercase" id="exampleModalLabel">{{$title}}</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="{{$action->value === Action::READ ? 'GET' : 'POST'}}" action="{{$route}}" enctype="multipart/form-data">
                @csrf
                @method($action->value)

                <div class="modal-body">
                    {{$slot}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>

                    @if($route)
                        <button type="submit" class="btn btn-primary text-white">Submit</button>
                    @else
                        {{$submit ?? ''}}
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
