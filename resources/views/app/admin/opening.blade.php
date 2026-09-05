@extends('layouts.admin')

@section('title', __('admin.opening_title'))

@section('content')
    @unless($ready)
        <x-admin.panel :description="__('admin.pending_migrations')">
            <p class="admin-notice">
                <code>php artisan migrate</code>
            </p>
        </x-admin.panel>
    @endunless

    {{-- 1. The switch: is the auberge open right now, and why not. --}}
    <x-admin.panel
        :title="__('admin.status_title')"
        :description="__('admin.status_description')"
    >
        <x-slot:actions>
            <x-admin.action
                :name="Modal::ADMIN_STATUS"
                :action="Action::UPDATE"
                :icon="$status->closedManually() ? Icon::OPEN : Icon::CLOSED"
                :label="$status->closedManually() ? __('admin.action.reopen') : __('admin.action.close')"
            />
        </x-slot:actions>

        <div @class(['admin-status', 'is-closed' => $status->isClosed()])>
            <span class="admin-status__dot" aria-hidden="true"></span>

            <div>
                <p class="admin-status__state">
                    {{ $status->isClosed() ? __('admin.status.closed') : __('admin.status.open') }}
                </p>

                <p class="admin-status__reason">
                    @if($status->closedManually())
                        {{ __('admin.status.closed_manually') }}
                    @elseif($holiday = $status->holiday())
                        {{ __('admin.status.closed_by_calendar', [
                            'from' => $holiday->starts_on->isoFormat('LL'),
                            'to' => $holiday->ends_on->isoFormat('LL'),
                        ]) }}
                    @else
                        {{ __('admin.status.open_hint') }}
                    @endif
                </p>
            </div>
        </div>
    </x-admin.panel>

    <x-modal.index
        :name="Modal::ADMIN_STATUS"
        :action="Action::UPDATE"
        :title="$status->closedManually() ? __('admin.action.reopen') : __('admin.action.close')"
        :route="route('admin.opening.toggle')"
    >
        <p class="admin-confirm">
            {{ $status->closedManually() ? __('admin.confirm.reopen') : __('admin.confirm.close') }}
        </p>

        <x-slot:submit>
            <button type="submit" @class(['btn', $status->closedManually() ? 'btn-primary' : 'btn-danger'])>
                {{ $status->closedManually() ? __('admin.action.reopen') : __('admin.action.close') }}
            </button>
        </x-slot:submit>
    </x-modal.index>

    {{-- 2. The calendar: closures planned ahead of time. --}}
    <x-admin.panel
        :title="__('admin.holidays_title')"
        :description="__('admin.holidays_description')"
        :padding="false"
    >
        <x-slot:actions>
            <x-admin.action
                :name="Modal::ADMIN_HOLIDAY"
                :action="Action::CREATE"
                :label="__('admin.action.add_holiday')"
            />
        </x-slot:actions>

        @if($holidays->isEmpty())
            <p class="admin-empty">{{ __('admin.empty.holidays') }}</p>
        @else
            <ul class="admin-holidays">
                @foreach($holidays as $holiday)
                    <li @class(['admin-holiday', 'is-current' => $holiday->isCurrent(), 'is-past' => $holiday->isPast()])>
                        <span class="admin-holiday__range">
                            @if($holiday->starts_on->isSameDay($holiday->ends_on))
                                {{ $holiday->starts_on->isoFormat('LL') }}
                            @else
                                {{ $holiday->starts_on->isoFormat('LL') }} → {{ $holiday->ends_on->isoFormat('LL') }}
                            @endif
                        </span>

                        <span class="admin-holiday__badge">
                            @if($holiday->isCurrent())
                                {{ __('admin.holiday.current') }}
                            @elseif($holiday->isPast())
                                {{ __('admin.holiday.past') }}
                            @else
                                {{ __('admin.holiday.upcoming', ['days' => (int) today()->diffInDays($holiday->starts_on)]) }}
                            @endif
                        </span>

                        <p class="admin-holiday__reason">
                            {{ $holiday->locale?->reason ?: __('admin.holiday.no_reason') }}
                        </p>

                        <div class="admin-holiday__actions">
                            <x-admin.action
                                :name="Modal::ADMIN_HOLIDAY"
                                :action="Action::UPDATE"
                                :iterator="$holiday->id"
                                :compact="true"
                            />

                            <x-admin.action
                                :name="Modal::ADMIN_HOLIDAY"
                                :action="Action::DELETE"
                                :iterator="$holiday->id"
                                :compact="true"
                            />
                        </div>

                        <x-modal.index
                            :name="Modal::ADMIN_HOLIDAY"
                            :action="Action::UPDATE"
                            :iterator="$holiday->id"
                            :title="__('admin.action.edit_holiday')"
                            :route="route('admin.holiday.update', $holiday)"
                        >
                            <x-admin.holiday-fields :holiday="$holiday"/>
                        </x-modal.index>

                        <x-modal.index
                            :name="Modal::ADMIN_HOLIDAY"
                            :action="Action::DELETE"
                            :iterator="$holiday->id"
                            :title="__('admin.action.delete')"
                            :route="route('admin.holiday.delete', $holiday)"
                        >
                            <p class="admin-confirm">
                                {{ __('admin.confirm.delete_holiday', [
                                    'from' => $holiday->starts_on->isoFormat('LL'),
                                    'to' => $holiday->ends_on->isoFormat('LL'),
                                ]) }}
                            </p>

                            <x-slot:submit>
                                <button type="submit" class="btn btn-danger">
                                    {{ __('admin.action.delete') }}
                                </button>
                            </x-slot:submit>
                        </x-modal.index>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-admin.panel>

    <x-modal.index
        :name="Modal::ADMIN_HOLIDAY"
        :action="Action::CREATE"
        :title="__('admin.action.add_holiday')"
        :route="route('admin.holiday.create')"
    >
        <x-admin.holiday-fields/>
    </x-modal.index>
@endsection
