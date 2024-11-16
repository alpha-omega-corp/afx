<div class="app-repeater" x-data="repeater('{{json_encode($items)}}')">
    <div class="app-repeater__header">
        <h6>{{$title}}</h6>
    </div>

    <div class="app-repeater__content">

        <template x-for="(item, index) in values" :key="index">
            <div class="app-repeater__content--item">
                <div class="w-100">
                    {{$slot}}

                    <input type="hidden" name="ids[]" x-model="item.id" />
                </div>

                <button type="button" @click="remove(index)" class="btn btn-danger h-100">
                    @svg('heroicon-o-trash')
                </button>
            </div>
        </template>
    </div>

    <div @click="add()" class="btn btn-primary w-100 text-white">
        @svg('heroicon-o-plus')
    </div>
</div>
