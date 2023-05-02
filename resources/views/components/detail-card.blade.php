<div class="card">  
  <div class="card-body">
        <div class="card-title"><h5><strong>{{ $title }}</strong><h5></div>
        @foreach ($body as $key => $value)
            @switch($key)
                @case('type')
                    <div class="row py-2">
                        <div class="col-2">
                            {{ ucwords(str_replace('_', ' ', $key ) ) }} 
                        </div>
                        <div class="col">
                            {{ $ridertypes[$value] }}
                        </div>
                    </div>
                @break
                @default
                    <div class="row py-2">
                        <div class="col-2">
                            {{ ucwords(str_replace('_', ' ', $key ) ) }} 
                        </div>
                        <div class="col">
                            {{ $value }}
                        </div>
                    </div>
            @endswitch            
        @endforeach    
        <?php 
            $back_route = $route .'.index'; 
        ?>
        <div class="pull-right py-2">
            <a class="btn btn-primary" href="{{route($back_route)}}"> Back</a>
        </div>    
  </div>
</div>