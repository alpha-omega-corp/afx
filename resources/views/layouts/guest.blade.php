<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{__('app.title')}}</title>

    @vite(['resources/js/app.js'])

</head>
<body>

@include('components.navigation')
@yield('content')

</body>

<x-footer />

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.data('gallery', (count) => ({

            init() {
                for (let i = 0; i < count; i++) {
                    const image = document.getElementById(`galleryImage${i}`)
                    image.parentElement.setAttribute('data-pswp-height', image.naturalHeight)
                    image.parentElement.setAttribute('data-pswp-width', image.naturalWidth)
                }
            }

        }))

    });

</script>

</html>
