{{-- The serving week, one fieldset a day, Monday first.
     Marking a day closed hides its boxes rather than emptying them, so a day
     shut for a fortnight can be reopened without typing its hours again — the
     request clears them on the way in, and they come back from the published
     week the next time the day is opened. --}}
<div class="admin-hours-fields">
    @foreach($days as $day)
        <fieldset class="admin-hours-day" x-data="{ closed: {{ $day->closed ? 'true' : 'false' }} }">
            <legend class="admin-hours-day__name">{{ Str::ucfirst($day->name()) }}</legend>

            {{-- The unchecked box sends nothing, so a `0` goes ahead of it:
                 without this, reopening a day would look like no change. --}}
            <input type="hidden" name="days[{{ $day->day }}][closed]" value="0">

            <label class="admin-check">
                <input type="checkbox" name="days[{{ $day->day }}][closed]" value="1" x-model="closed" @checked($day->closed)>
                <span>{{ __('admin.field.closed_all_day') }}</span>
            </label>

            <div class="admin-hours-day__ranges" x-show="! closed" x-cloak>
                <x-forms.date
                    type="time"
                    :name="'days[' . $day->day . '][lunch_from]'"
                    :label="__('admin.field.lunch_from')"
                    :value="$day->lunch_from"
                    :required="false"
                />

                <x-forms.date
                    type="time"
                    :name="'days[' . $day->day . '][lunch_to]'"
                    :label="__('admin.field.lunch_to')"
                    :value="$day->lunch_to"
                    :required="false"
                />

                <x-forms.date
                    type="time"
                    :name="'days[' . $day->day . '][dinner_from]'"
                    :label="__('admin.field.dinner_from')"
                    :value="$day->dinner_from"
                    :required="false"
                />

                <x-forms.date
                    type="time"
                    :name="'days[' . $day->day . '][dinner_to]'"
                    :label="__('admin.field.dinner_to')"
                    :value="$day->dinner_to"
                    :required="false"
                />
            </div>
        </fieldset>
    @endforeach
</div>
