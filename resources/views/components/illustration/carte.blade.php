{{-- An open carte standing on the table, a candle lit beside it: la carte. --}}
<svg {{ $attributes->merge(['class' => 'illustration']) }} viewBox="0 0 180 128" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="{{ __('illustration.carte') }}" focusable="false">
    <ellipse class="ill-glow" cx="92" cy="106" rx="76" ry="18"/>
    <path d="M24 30l62 10v62l-62 10Z"/>
    <path d="M86 40l62-10v82l-62-10"/>
    <g transform="translate(24 30) skewY(9.2)">
        <path d="M21 14h20"/>
        <path d="M9 30h20M9 44h15M9 58h22"/>
        <path stroke-dasharray="0 5" d="M34 30h12M29 44h17M36 58h10"/>
        <path d="M51 30h2M51 44h2M51 58h2"/>
    </g>
    <g transform="translate(86 40) skewY(-9.2)">
        <path d="M21 14h20"/>
        <path d="M9 30h18M9 44h22M9 58h14"/>
        <path stroke-dasharray="0 5" d="M32 30h14M36 44h10M28 58h18"/>
        <path d="M51 30h2M51 44h2M51 58h2"/>
    </g>
    <rect x="158" y="84" width="8" height="30" rx="1.5"/>
    <path d="M162 84v-4M152 114h20"/>
    <path class="ill-lantern" d="M162 78c-5-5-4-11 0-17 4 6 5 12 0 17Z"/>
    <path d="M8 114h140"/>
</svg>
