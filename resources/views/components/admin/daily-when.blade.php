@props(['dish'])

{{-- The card's days in words, for staff: "du lundi 23 au vendredi
     27 novembre", "à partir du…", "jusqu'au…", or "jusqu'à nouvel ordre". --}}
@php
    $format = fn ($day) => $day->locale(app()->getLocale())->isoFormat('dddd D MMMM');
    $from = $dish->daily_on;
    $until = $dish->daily_until;

    $key = match (true) {
        $from && $until && $from->isSameDay($until) => 'day',
        $from && $until => 'range',
        (bool) $from => 'from',
        (bool) $until => 'until',
        default => 'always',
    };
@endphp

<span {{ $attributes }}>{{ __('admin.daily_when.' . $key, [
    'from' => $from ? $format($from) : '',
    'until' => $until ? $format($until) : '',
]) }}</span>
