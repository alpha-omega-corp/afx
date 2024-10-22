<div class="input-group mb-3">
    @if($icon)
        <div class="input-group-text">
            @svg($icon->value, 'w-6 h-6')
        </div>
    @endif


    <div class="form-floating">
        @if($model)
            <input required
                   type="{{$type}}"
                   value="{{$value}}"
                   name="{{$name}}"
                   x-model="{{$model}}"
                   class="form-control"
                   id="floatingInput{{$id}}">
        @else
            <input required
                   type="{{$type}}"
                   value="{{$value}}"
                   name="{{$name}}"
                   class="form-control"
                   id="floatingInput{{$id}}">
        @endif
        <label for="floatingInput{{$id}}">{{$label}}</label>
    </div>
</div>
