<div class="modal fade" id="{{$id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p class="text-danger">Are you sure delete this Item?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn bg-muted text-white" data-bs-dismiss="modal">Close</button>
            <!-- <button type="button" class="btn btn-primary confirmButton">Confirm</button> -->
            {!! Form::open(['method' => 'DELETE', 'route' => [$route, $deletedataid], 'style' => 'display:inline']) !!}
                {!! Form::submit('Confirm', [
                    'class' => 'btn delmodal-comfirm-btn cs-bg-primary text-white',
                ]) !!}
            {!! Form::close() !!}
        </div>
        </div>
    </div>
</div>