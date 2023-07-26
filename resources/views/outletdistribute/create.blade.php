@extends('layouts.app')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Outlet Distribute</h4>
        </div>
         @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
        @endif

         <div class="errorbox"></div>

        {!! Form::open(array('route' => 'outletdistribute.store', 'method' => 'post', 'id' => 'outletdistribute')) !!}
            @csrf
            <div class="p-4 rounded border shadow-sm mb-5">
                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        {!! Form::label('date', 'Date', array('class' => 'form-label')) !!}
                        {!! Form::date('date', null, array('class' => 'form-control', 'id'=>'date')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('reference_No', 'Reference No.', array('class' => 'form-label')) !!}
                        {!! Form::text('reference_No', $generatedRef, array('class' => 'form-control', 'id' => 'reference')) !!}
                    </div>
                    <!-- <div class="col-md-4">
                        {!! Form::label('status', 'Status', array('class' => 'form-label')) !!}
                        {!! Form::select('status', $ds_status, null, array('placeholder' => 'Choose to status', 'class' => 'form-control','id'=>'status')) !!}
                    </div> -->
                    <div class="col-md-4">
                        {!! Form::label('from_outlet', 'From (Outlet)', array('class' => 'form-label')) !!}
                        {!! Form::hidden('from_outlet', $id) !!}
                        {!! Form::select('from_outlet_select',$outlets, $id, array('placeholder' => 'Choose From outlets', 'class' => 'form-control','id'=>'fromOutlet', 'disabled' => 'disabled')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('counterMachine', 'To (Counter / Machine)', array('class' => 'form-label')) !!}
                        {!! Form::select('counterMachine', $counter_machine, null, array('placeholder' => 'Choose', 'class' => 'form-control counterMachine','id'=>'counterMachine')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('counter', 'Counter', array('class' => 'form-label')) !!}
                        {!! Form::select('to_counter', $counter, null, array('placeholder' => 'Choose', 'class' => 'form-control counter','id'=>'counter', 'disabled')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('machine', 'Machine', array('class' => 'form-label')) !!}
                        {!! Form::select('to_machine', $machines, null, array('placeholder' => 'Choose', 'class' => 'form-control machine','id'=>'machine', 'disabled')) !!}
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-4">Add Products</h5>
            <div class="input-group rounded w-25 mb-3">
                <div>
                    <!-- <input type="hidden" id="outletdistribute_id" value=""> -->
                    <input type="text" class="form-control" id="outletdistir_searchInput" data-id="{{ $id }}" placeholder="Search...">
                    <div id="searchResults"></div>
                </div>
            </div>

            <div id="show_Product">
                <table class="table table-bordered text-center shadow rounded outdstable" id='outds_itemTable'>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 30%;">Product Name</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Purchased Price</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-4 px-4 justify-content-end my-5">
                <div class="">
                    {!! Form::label('remark', 'Remark', ['class' => 'form-label']) !!}
                    {!! Form::textarea('remark', null, [
                        'class' => 'form-control',
                        'id' => 'remark',
                        'cols' => '40',
                        'rows' => '4',
                    ]) !!}
                </div>
                <div class="align-items-center d-flex">
                    <h4 class="fw-bolder">Total Amount: <span id="total"
                            class="ms-3 inline-block text-blue">0</span></h4>
                </div>
            </div>
            
        {!! Form::close() !!}

        <div class="text-center my-5">
            <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <!-- <button type="submit" form="outletdistribute" class="btn btn-blue ms-2">Next</button> -->
            <button type="submit" form="outletdistribute" class="btn btn-blue ms-2" id="outletDsbutton">Save</button>
        </div>
            
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">Are you sure delete this Item</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-muted text-white" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn delmodal-comfirm-btn cs-bg-primary text-white confirmButton">Confirm</button>
            </div>
            </div>
        </div>
    </div>
    


@endsection
