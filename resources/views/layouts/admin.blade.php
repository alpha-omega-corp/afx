<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <title>{{__('app.title')}}</title>

    @vite(['resources/js/app.js'])

</head>
<body>

@include('components.navigation')

<div class="app-admin">
    <div class="app-admin__header">
    </div>

    <div class="d-flex">

        <div class="app-admin__navigation-container">
            <ul class="app-admin__navigation">
                <li @class(['active-item' => Request::is('admin/home')])>
                    <a href="{{route('admin.home')}}">{{__('nav.home')}}</a>
                </li>
                <li @class(['active-item' => Request::is('admin/menu')])>
                    <a href="{{route('admin.menu')}}">{{__('nav.menu')}}</a>
                </li>
                <li @class(['active-item' => Request::is('admin/restaurant')])>
                    <a href="{{route('admin.restaurant')}}">{{__('nav.restaurant')}}</a>
                </li>
                <li @class(['active-item' => Request::is('admin/hotel')])>
                    <a href="{{route('admin.hotel')}}">{{__('nav.hotel')}}</a>
                </li>
                <li @class(['active-item' => Request::is('admin/contact')])>
                    <a href="{{route('admin.contact')}}">{{__('nav.contact')}}</a>
                </li>
            </ul>
        </div>

        <main class="app-admin__content">
            @yield('content')
        </main>
    </div>
</div>


</body>

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.store('repeater', {
            removed: [],
        })

        Alpine.data('repeater', (data) => ({
            values: [],
            created: [],

            init() {
                if (data) {
                    this.values = JSON.parse(data)
                }
                console.log(this.values)
            },
            add() {
                this.values.push({
                    item: '',
                });

                this.created.push(this.values.length - 1)
            },
            remove(index) {
                console.log(this.values[index].id)
                this.$store.repeater.removed.push(this.values[index].id)
                this.values.splice(index, 1);
            },
        }))

        Alpine.data('repeaterDelete', () => ({

            async remove() {
                const items = this.$store.repeater.removed
                if (items.length === 0) return

                await $.ajax({
                    url: '{{route('admin.menu.remove')}}',
                    type: 'DELETE',
                    data : {
                        "_token": $('#csrf-token')[0].content,
                        "items": items
                    },
                })
            }
        }))

        Alpine.data('images', (input) => ({
            input: document.getElementById(input),
            selectables: document.querySelectorAll('.gallery-select'),
            selected: [],

            init() {
                this.input.files = null
                this.upload()
                this.selectables.forEach(item => {
                    item.checked = false
                })
            },

            actions() {
                let selected = []

                this.selectables.forEach(item => {
                    if(item.checked) selected.push(item.id)
                })

                this.selected = selected
                console.log(this.selected)
            },

            select(id) {
                let item = $(`#select-${id}`)
                item.click()
                this.actions()
            },

            upload() {
                const files = this.input.files

                for (let i = 0; i < files.length; i++) {
                    let reader = new FileReader()

                    reader.onload = (e) => {
                        let img = document.createElement('img')
                        img.src = `${e.target.result}`
                        img.classList.add('gallery__photo')

                        document.querySelector('.app-gallery__container').appendChild(img)
                    }

                    reader.readAsDataURL(files[i])
                }
            },

            async remove() {
                let items = this.selected.map(id => +id.split('-')[1])

                await $.ajax({
                    url: '{{route('gallery.delete')}}',
                    type: 'DELETE',
                    data : {
                        "_token": $('#csrf-token')[0].content,
                        "items": items,
                    },
                    success: () => window.location.reload()
                })
            }
        }))
    })

</script>


</html>
