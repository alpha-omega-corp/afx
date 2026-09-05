<section class="admin-panel">
    <header class="admin-panel__header">
        <div class="admin-panel__heading">
            @if($title)
                <h2 class="admin-panel__title">{{ $title }}</h2>
            @endif

            @if($description)
                <p class="admin-panel__description">{{ $description }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="admin-panel__actions">
                {{ $actions }}
            </div>
        @endisset
    </header>

    <div @class(['admin-panel__body', 'admin-panel__body--padded' => $padding])>
        {{ $slot }}
    </div>
</section>
