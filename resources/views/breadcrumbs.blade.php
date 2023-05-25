<nav aria-label="breadcrumb">
    <ol class="breadcrumb m-0">
        @foreach($breadcrumbs as $breadcrumb)
            @if(!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                    <span class="dot rounded d-inline-block ms-2 me-2"></span>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
