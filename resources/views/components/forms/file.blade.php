<div class="mb-3 w-100">
    <!-- Upload -->
    <div id="imageUpload-{{$name}}" class="file-upload"
         x-data="{input: document.getElementById('imageSelect-{{$name}}')}" @click="input.click()">
        @svg('heroicon-o-cloud-arrow-down')
        <span>{{$label}}</span>
    </div>

    <!-- Preview -->
    <div id="imageLabel-{{$name}}" class="file-preview" style="display:none;">
        <div class="file-label">
            <div class="preview-icon">
                @svg('heroicon-o-photo')
            </div>
            <div class="preview-label">{{$label}}</div>
        </div>

        <a class="preview-edit" x-data="{input: document.getElementById('imageSelect-{{$name}}')}"
           @click="input.click()">
            @svg(Icon::EDIT->value)
        </a>

        <img class="image-preview" id="imagePreview-{{$name}}" src="#" alt="your image"/>
    </div>

    <!-- File Input -->
    <input type="file" name="{{$name}}" value="{{$file}}" id="imageSelect-{{$name}}" class="d-none" />

</div>

<script type="module">
    document.getElementById('imageSelect-{{$name}}').onchange = () => {
        const [file] = document.getElementById('imageSelect-{{$name}}').files
        if (file) {
            document.getElementById('imageLabel-{{$name}}').style.display = 'block';
            document.getElementById('imagePreview-{{$name}}').src = URL.createObjectURL(file)
            document.getElementById('imageUpload-{{$name}}').style.display = 'none';
        }
    }

    if ('{{$file}}') {
        document.getElementById('imageUpload-{{$name}}').style.display = 'none';
        document.getElementById('imagePreview-{{$name}}').src = '{{asset($file)}}';
        document.getElementById('imageLabel-{{$name}}').style.display = 'block';
    }
</script>
