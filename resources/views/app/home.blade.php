@extends('layouts.guest')

@section('content')
    <x-page image="{{$page->image}}">

        <x-slot:title>
            <h1>{{$page->locale->title}}</h1>

            <div class="booking">
                <a href="https://wa.me/+41786857845" >
                    {{__('app.booking')}}
                </a>
            </div>
        </x-slot:title>

        <x-section :color="Color::DARK">
            <div class="description">

                <div class="description-image animation-grow">
                    <img src="{{asset($page->image)}}" alt="{{$page->image}}"/>
                </div>

                <div class="d-flex flex-column">
                    <p>{{$page->locale->content}}</p>
                    <h2 class="text">{{__('app.delicacies')}}</h2>
                </div>

            </div>
        </x-section>


        <x-section
            :color="Color::LIGHT"
        >
            <x-carousel name="foods" :count="4">
                @foreach($images as $image)
                    <li class="glide__slide">
                        <div class="delicacies">
                            <img src="{{asset($image)}}" alt="{{$image}}"/>
                        </div>
                    </li>
                @endforeach
            </x-carousel>
        </x-section>



    </x-page>
@endsection

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data('carousel', (name, count) => ({
            init() {
                const glide = new Glide(`#${name}`, {
                    type: 'carousel',
                    perView: count,
                    breakpoints: {
                        1900: {
                            perView: count - 1,
                        },
                        1300: {
                            perView: count - 2,
                        },
                        900: {
                            perView: 1,
                        },
                    },
                })

                this.$nextTick(() => {
                    glide.mount(GlideControls)
                    console.log('mounted')
                })
            },
        }))
    });
</script>
