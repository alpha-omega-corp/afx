
<div class="input-group mb-3">
        <div class="input-group-text">
            @svg($icon->value, 'w-6 h-6')
        </div>


    <div class="form-floating">
        <input type="{{$type}}" value="{{$value}}" name="{{$name}}" class="form-control" id="floatingInput{{$name}}" placeholder="name@example.com">
        <label for="floatingInput{{$name}}">{{$label}}</label>
    </div>
</div>
