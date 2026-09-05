<ul class="admin-nav__list">
    @foreach(\App\Support\AdminSections::all() as $section)
        @php($current = request()->routeIs($section['route']))

        <li>
            <a
                href="{{ route($section['route']) }}"
                @class(['admin-nav__link', 'is-current' => $current])
                @if($current) aria-current="page" @endif
            >
                @svg($section['icon'], 'admin-nav__icon')
                <span>{{ ucfirst($section['label']) }}</span>
            </a>
        </li>
    @endforeach
</ul>
