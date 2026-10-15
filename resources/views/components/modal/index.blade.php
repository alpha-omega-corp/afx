{{-- A modal is always one action on one record. It carries a form only when
     that action posts somewhere; read-only and JS-driven modals do not.

     No static `role="dialog"` / `aria-modal`: Bootstrap sets both when it
     opens the dialog and strips them when it closes, so writing them here
     would only leave a hidden container announcing itself as a dialog. --}}
<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Sized and tracked in _modal.scss rather than with utility
                     classes, so the dialog's title and a panel's title cannot
                     drift apart. --}}
                <h2 class="modal-title" id="{{ $id }}-title">{{ $title }}</h2>

                {{-- Deliberately not `btn-close-white`: that class recolours
                     the glyph with `filter: invert(1) …`, and a filter
                     repaints everything the element draws — the ember focus
                     ring came out white, i.e. the one control in the build
                     where the focus colour was not the focus colour. The
                     glyph is tinted in _modal.scss instead. --}}
                <button
                    type="button"
                    class="btn-close"
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
