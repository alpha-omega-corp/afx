@extends('layouts.admin')

@section('title', __('admin.opening_title'))

@section('content')
    {{-- 0. The three numbers this screen exists to answer, before any panel
           has to be read: are we open, how many days do we serve this week,
           and when is the next time the door is shut on purpose. Every one of
           them is derived from what the controller already passed — no extra
           query, no extra column. --}}
    @php($next = $holidays->first(fn ($holiday) => ! $holiday->isPast()))

    <ul class="admin-metrics">
        <li @class(['admin-metric', 'admin-metric--shut' => $status->isClosed(), 'admin-metric--ok' => ! $status->isClosed()])>
            <p class="admin-metric__label">{{ __('admin.metric.status') }}</p>
            <p class="admin-metric__value">
                {{ $status->isClosed() ? __('admin.status.closed') : __('admin.status.open') }}
            </p>
        </li>

        <li class="admin-metric">
            <p class="admin-metric__label">{{ __('admin.metric.days_open') }}</p>
            <p class="admin-metric__value u-tabular">{{ $hours->reject->isClosed()->count() }} / 7</p>
        </li>

        <li class="admin-metric">
            <p class="admin-metric__label">{{ __('admin.metric.next_closure') }}</p>
            <p class="admin-metric__value u-tabular">{{ $next?->starts_on->isoFormat('LL') ?? '—' }}</p>
            @if($next)
                <p class="admin-metric__note">
                    {{ $next->isCurrent()
                        ? __('admin.holiday.current')
                        : __('admin.holiday.upcoming', ['days' => (int) today()->diffInDays($next->starts_on)]) }}
                </p>
            @endif
        </li>
    </ul>

    @unless($ready)
        <x-admin.panel :description="__('admin.pending_migrations')">
            <p class="admin-notice">
                <code>php artisan migrate</code>
            </p>
        </x-admin.panel>
    @endunless

    {{-- 1. The switch: is the auberge open right now, and why not.
           Three lines and a button — a third of the row is all it needs, and
           the wide half beside it goes to the week. The tile is heated only
           when the auberge is shut BY HAND: being open is the normal state and
           does not announce itself, and a closure that came from the calendar
           is already marked, in its place, by the .is-current row further
           down. isClosed() is true for either reason, so heating the tile on
           it would put two L3 surfaces on the screen the day a planned
           closure starts — and two heated surfaces mean neither of them. --}}
    <x-admin.panel
        :title="__('admin.status_title')"
        :description="__('admin.status_description')"
        :tone="$status->closedManually() ? 'ember' : 'glass'"
        :xl="4"
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

    {{-- 2. The week: the hours printed in the footer and on the contact
           page, and the days the plat du jour has to step over. Two internal
           columns that only breathe past ~700px, so it takes the wide two
           thirds of the row. --}}
    <x-admin.panel
        :title="__('admin.hours_title')"
        :description="__('admin.hours_description')"
        :padding="false"
        :xl="8"
    >
        <x-slot:actions>
            <x-admin.action
                :name="Modal::ADMIN_HOURS"
                :action="Action::UPDATE"
                :icon="Icon::CALENDAR"
                :label="__('admin.action.edit_hours')"
            />
        </x-slot:actions>

        <ul class="admin-hours">
            @foreach($hours as $day)
                <li @class(['admin-hours__row', 'is-closed' => $day->isClosed()])>
                    <span class="admin-hours__day">{{ Str::ucfirst($day->name()) }}</span>

                    <span class="admin-hours__service">
                        {{ $day->serviceLine() ?? __('admin.status.closed') }}
                    </span>
                </li>
            @endforeach
        </ul>

        {{-- The two lines a guest actually reads, shown here so a change can
             be checked without leaving the page. --}}
        <p class="admin-hours__preview">
            @foreach(App\Support\Opening::schedule() as $line)
                <span>{{ $line }}</span>
            @endforeach
        </p>
    </x-admin.panel>

    <x-modal.index
        :name="Modal::ADMIN_HOURS"
        :action="Action::UPDATE"
        :title="__('admin.action.edit_hours')"
        :route="route('admin.opening.hours')"
    >
        <x-admin.hours-fields :days="$hours"/>
    </x-modal.index>

    {{-- 3. The calendar: closures planned ahead of time. Full width — a
           period is a date range, a badge, a reason and two buttons on one
           line, and cutting that line in half is how the reason disappears.

           flat: the calendar is the list that grows without bound on this
           screen, and it holds the one row that may carry ember. Two blurred
           layers are already on screen (the rail and the bar); a third, this
           tall, is what the budget gives up first. --}}
    <x-admin.panel
        :title="__('admin.holidays_title')"
        :description="__('admin.holidays_description')"
        :padding="false"
        tone="flat"
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
                        <span class="admin-holiday__range u-tabular">
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
