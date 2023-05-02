<?php 
    $route = $route .'.create'; 
?>
<div class="row">
    <div class="col-lg-12 margin-tb">        
        <div class="pull-right py-3">          
          <a class="create-div" href="{{ route($route) }}"><i class="fa-solid fa-circle-plus"></i> {{ $label }} </a>
        </div>
    </div>
</div>