<div {{$attributes->merge(['class' => 'alert alert-'.$type])}}>
    {{ $message }}
</div>

<div {{$attributes}}>
    {{ $message }}
</div>