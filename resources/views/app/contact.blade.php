@extends('layouts.guest')

@section('content')

    <x-page :image="$page->image">

        <x-slot:title>
            <h1>{{$page->locale->title}}</h1>
        </x-slot:title>



        <div class="container">

            <div class="app-page__description">
                {{$page->locale->content}}
            </div>

            <div class="app-contact">

                <div class="app-contact__informations">
                    <h3 class="app-contact__informations--title">{{__('app.coordinates')}}</h3>

                    <div class="app-contact__informations--item">
                        @svg(Icon::PIN->value)
                        <a href="https://www.google.ch/maps/place/Grand-Rue+31,+1297+Founex/@46.3325566,6.1902006,17z/">
                            Grand'Rue 31 1297 Founex
                        </a>
                    </div>

                    <hr>

                    <div class="app-contact__informations--item">
                        @svg(Icon::PHONE->value)
                        <a href="tel:022 776 10 29">
                            022 776 10 29
                        </a>
                    </div>

                    <div class="app-contact__informations--item">
                        @svg(Icon::EMAIL->value)
                        <a href="mailto:aubergedefounex@bluewin.ch">
                            aubergedefounex@bluewin.ch
                        </a>
                    </div>

                    <hr>

                    <div class="app-contact__informations--item">
                        <img src="{{Vite::image('facebook.png')}}" alt="contact icon"/>
                        <a href="https://www.facebook.com/AubergeFounex" target="_blank">Auberge de Founex</a>
                    </div>

                    <div class="app-contact__informations--item">
                        <img src="{{Vite::image('instagram.png')}}" alt="contact icon"/>
                        <a href="https://www.instagram.com/auberge_de_founex/" target="_blank">auberge_de_founex</a>
                    </div>

                </div>

                <form method="POST" action="{{route('contact.store')}}" class="app-contact__form shadow-lg">

                    <div class="d-flex justify-content-center pb-4">
                        <img class="app-contact__icon" src="{{Vite::image('contact.png')}}" alt="contact icon"/>
                    </div>
                    @csrf

                    <x-forms.input name="name" :label="__('form.name')"/>

                    <x-forms.input name="email" :label="__('form.email')"/>

                    <x-forms.input name="phone" :label="__('form.phone')"/>

                    <x-forms.text name="message" :label="__('form.message')"/>

                    <hr>

                    <button type="submit" class="btn btn-primary text-white w-100">
                        {{__('form.submit')}}
                    </button>
                </form>
            </div>


        </div>


    </x-page>
@endsection
