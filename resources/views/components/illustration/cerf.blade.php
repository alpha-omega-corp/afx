{{-- A stag's head, antlers lit: la chasse. Each antler stroke is measured
     as 1 (`pathLength`) so the sign can draw them in, beam first, then
     the tines, without knowing how long any of them really is. --}}
<svg {{ $attributes->merge(['class' => 'illustration']) }} viewBox="0 0 160 128" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="{{ __('illustration.cerf') }}" focusable="false">
    <ellipse class="ill-glow" cx="80" cy="86" rx="46" ry="32"/>
    <g class="ill-accent ill-antlers">
        <path pathLength="1" d="M71 60C58 53 45 45 41 31C38 22 42 13 50 6"/>
        <path pathLength="1" d="M63 54c-7 1-14-1-19-6"/>
        <path pathLength="1" d="M49 45c0-8 3-14 9-19"/>
        <path pathLength="1" d="M40 25c-5-2-9-7-10-13"/>
        <path pathLength="1" d="M89 60c13-7 26-15 30-29c3-9-1-18-9-25"/>
        <path pathLength="1" d="M97 54c7 1 14-1 19-6"/>
        <path pathLength="1" d="M111 45c0-8-3-14-9-19"/>
        <path pathLength="1" d="M120 25c5-2 9-7 10-13"/>
    </g>
    <path d="M66 62c6-4 22-4 28 0"/>
    <path d="M66 62c-2 11 0 23 6 35c3 7 13 7 16 0c6-12 8-24 6-35"/>
    <path d="M66 67c-9-7-20-8-27-3c6 7 16 9 26 7"/>
    <path d="M94 67c9-7 20-8 27-3c-6 7-16 9-26 7"/>
    <circle cx="71" cy="76" r="1.4" fill="currentColor"/>
    <circle cx="89" cy="76" r="1.4" fill="currentColor"/>
    {{-- A nose, not a mouth: a curve under the muzzle read as a grin. --}}
    <path d="M75 92c2-2 8-2 10 0c-1 2.5-3 3.5-5 3.5s-4-1-5-3.5Z" fill="currentColor"/>
</svg>
