{{-- The current entry is styled from [aria-current="page"] and from nothing
     else: there is no parallel .is-current class to fall out of step with the
     accessible state. Its three signals — the diluted ember wash, the ember
     rule down the inner edge and the weight — are carried by that attribute in
     pages/_admin.scss. --}}
<ul class="admin-nav__list">
    @foreach(\App\Support\AdminSections::all() as $section)
        @php($current = request()->routeIs($section['route']))

        <li>
            <a
                href="{{ route($section['route']) }}"
                class="admin-nav__link"
                @if($current) aria-current="page" @endif
            >
                @svg($section['icon'], 'admin-nav__icon', ['focusable' => 'false'])
                <span>{{ ucfirst($section['label']) }}</span>
            </a>
        </li>
    @endforeach
</ul>
