{{-- A modal is always one action on one record. It carries a form only when
     that action posts somewhere; read-only and JS-driven modals do not. --}}
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-6 text-uppercase" id="{{ $id }}-title">{{ $title }}</h2>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="{{ __('admin.action.cancel') }}"
                ></button>
            </div>

            @if($route)
                <form method="POST" action="{{ $route }}" enctype="multipart/form-data">
                    @csrf
                    @method($action->value)
            @endif

            <div @class(['modal-body', 'modal-body--padded' => $padding])>
                {{ $slot }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal">
                    {{ __('admin.action.cancel') }}
                </button>

                @isset($submit)
                    {{ $submit }}
                @elseif($route)
                    <button type="submit" class="btn btn-primary">{{ __('admin.action.save') }}</button>
                @endisset
            </div>

            @if($route)
                </form>
            @endif
        </div>
    </div>
</div>
