@extends('layouts.guest')

@section('content')
    <x-page image="{{$page->image}}">

        <x-slot:title>
            {{$page->locale->title}}
        </x-slot:title>

        <div style="height:1000px;font-size:36px;">
            Scroll Up and Down this page to see the parallax scrolling effect.
            This div is just here to enable scrolling.
            Tip: Try to remove the background-attachment property to remove the scrolling effect.
        </div>
    </x-page>

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
                    })
                },
            }))
        });
    </script>

@endsection

