@extends('layouts.guest')

@section('title', ucfirst(__('nav.contact')) . ' — ' . __('app.title'))

@section('content')
    <x-page :image="$page->image" :title="$page->locale?->title ?: ucfirst(__('nav.contact'))" :band="false" :rule="true" embers="quiet">

        @if($page->locale?->content)
            <div class="app-page__intro">
                <div class="container">
                    <p class="prose">{{ $page->locale->content }}</p>
                </div>
            </div>
        @endif

        <div class="container app-contact">
            <div class="app-contact__grid">

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
                                {{ __('footer.hours_week') }}<br>
                                {{ __('footer.hours_closed') }}
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

                <form method="POST" action="{{ route('contact.store') }}" class="app-contact__form">
                    @csrf

                    <p class="app-contact__text">{{ __('form.intro') }}</p>

                    @if(session('status'))
                        <p class="app-contact__status" role="status">{{ session('status') }}</p>
                    @endif

                    {{-- Before the captcha nothing could fail here, so nothing
                         said so and nothing was kept. Now that a question can
                         be got wrong, a rejected message has to come back with
                         its reason and with every word the sender typed. --}}
                    @if($errors->any())
                        <div class="app-contact__errors" role="alert">
                            <p>{{ __('form.errors') }}</p>

                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <x-forms.input name="name" :label="__('form.name')" :value="old('name')"/>
                    <x-forms.input name="email" type="email" :label="__('form.email')" :value="old('email')"/>
                    <x-forms.input name="phone" type="tel" :label="__('form.phone')" :value="old('phone')"/>
                    <x-forms.text name="message" :label="__('form.message')" :value="old('message')"/>

                    <x-forms.captcha/>

                    <button type="submit" class="btn btn-primary w-100">
                        {{ __('form.send') }}
                    </button>
                </form>
            </div>

            {{-- The street, and one tap to being taken there. --}}
            <x-map/>
        </div>
    </x-page>
@endsection
