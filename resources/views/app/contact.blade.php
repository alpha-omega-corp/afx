@extends('layouts.guest')

@section('title', ucfirst(__('nav.contact')) . ' — ' . __('app.title'))

@section('content')
    <x-page :image="$page?->image" :title="$page?->locale?->title ?: ucfirst(__('nav.contact'))" :band="false" :rule="true" embers="quiet">

        @if($page?->locale?->content)
            <div class="app-page__intro">
                <div class="container">
                    <p class="prose">{{ $page->locale->content }}</p>
                </div>
            </div>
        @endif

        <div class="container app-contact">
            {{-- The details and nothing else. There was a message form here;
                 it has been taken out, so the page is now what a guest opens
                 it for — where the auberge is, when it serves, and the two
                 ways to reach it that get an answer the same day. --}}
            <div class="app-contact__information">
                <h2 class="app-section__title">{{ __('footer.visit') }}</h2>

                <dl class="app-contact__list">
                    <div class="app-contact__row">
                        <dt>{{ __('footer.address') }}</dt>
                        <dd>
                            <a href="https://www.google.ch/maps/place/Grand-Rue+31,+1297+Founex/@46.3325566,6.1902006,17z/" target="_blank" rel="noopener">
                                Grand'Rue 31, 1297 Founex
                            </a>
                        </dd>
                    </div>

                    <div class="app-contact__row">
                        <dt>{{ __('form.phone') }}</dt>
                        <dd><a href="tel:+41227761029">022 776 10 29</a></dd>
                    </div>

                    <div class="app-contact__row">
                        <dt>{{ __('form.email') }}</dt>
                        <dd><a href="mailto:aubergedefounex@bluewin.ch">aubergedefounex@bluewin.ch</a></dd>
                    </div>

                    <div class="app-contact__row">
                        <dt>{{ __('footer.hours') }}</dt>
                        <dd>
                            @foreach(App\Support\Opening::schedule() as $line)
                                {{ $line }}@if(! $loop->last)<br>@endif
                            @endforeach
                        </dd>
                    </div>

                    <div class="app-contact__row">
                        <dt>{{ __('footer.social') }}</dt>
                        <dd>
                            <div class="app-contact__social">
                                <a href="https://www.facebook.com/AubergeFounex" target="_blank" rel="noopener">
                                    <x-icon.facebook/> Auberge de Founex
                                </a>
                                <a href="https://www.instagram.com/auberge_de_founex/" target="_blank" rel="noopener">
                                    <x-icon.instagram/> auberge_de_founex
                                </a>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- The street, and one tap to being taken there. --}}
            <x-map/>
        </div>
    </x-page>
@endsection
