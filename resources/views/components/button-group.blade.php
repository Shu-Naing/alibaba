<div class="button-group">
    @foreach ($buttons as $button)
       <button class="btn btn-secondary m-1"> <a href="{{ $button['url'] }}" class="button">{{ $button['label'] }}</a></button>
    @endforeach
</div>