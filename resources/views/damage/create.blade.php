@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create Damage</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="errorbox"></div>

        {!! Form::open([
            'route' => 'damage.store',
            'method' => 'post',
            'class' => '',
            'id' => 'damageForm',
        ]) !!}
            @csrf
            <div class="p-4 rounded border shadow-sm mb-5">
                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        {{ Form::label('date', 'Date', ['class' => 'form-label' . ($errors->has('date') ? ' text-danger' : '')]) }}
                        {{ Form::date('date', null, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'id' => 'date']) }}
                        @error('date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        {!! Form::label('outlet_id', 'Outlet Id', array('class' => 'form-label'. ($errors->has('outlet_id') ? ' text-danger' : ''))) !!}
                        {!! Form::select('outlet_id', $outlets, null, array('placeholder' => 'Choose outlets...', 'class' => 'form-control' . ($errors->has('outlet_id') ? ' is-invalid' : ''),'id'=>'demage_outlet_id')) !!}
                        @error('outlet_id')
                            <span class="text-danger">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('damage_no', 'Damage No', array('class' => 'form-label'.($errors->has('damage_no') ? ' text-danger' : ''))) !!}
                        {{ Form::text('damage_no', null, ['class' => 'form-control' . ($errors->has('damage_no') ? ' is-invalid' : ''), 'id' => 'damage_no','readonly']) }}
                        @error('damage_no')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('name', 'Name', array('class' => 'form-label'.($errors->has('name') ? ' text-danger' : ''))) !!}
                        {{ Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name']) }}
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror 
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('amount', 'Compensation Amount', ['class' => 'form-label'.($errors->has('amount') ? ' text-danger' : '')]) !!}
                        {!! Form::number('amount', null, ['class' => 'form-control'. ($errors->has('amount') ? ' is-invalid' : ''), 'id' => 'amount']) !!}
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('action', 'Action', array('class' => 'form-label'. ($errors->has('action') ? ' text-danger' : ''))) !!}
                        {!! Form::select('action', $action, null, array('placeholder' => 'Choose...', 'class' => 'form-control' . ($errors->has('action') ? ' is-invalid' : ''),'id'=>'open_action')) !!}
                        @error('action')
                            <span class="text-danger">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('error', 'Error', ['class' => 'form-label'.($errors->has('error') ? ' text-danger' : '')]) !!}
                        {!! Form::text('error', null, ['class' => 'form-control'. ($errors->has('error') ? ' is-invalid' : ''), 'id' => 'error']) !!}
                        @error('error')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('distination', 'Distination', array('class' => 'form-label'. ($errors->has('distination') ? ' text-danger' : ''))) !!}
                        {!! Form::select('distination', $distination, null, array('placeholder' => 'Choose...', 'class' => 'form-control' . ($errors->has('distination') ? ' is-invalid' : ''),'id'=>'open_distination')) !!}
                        @error('distination')
                            <span class="text-danger">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>
            </div>
            <h5 class="fw-bold mb-4">Add Products</h5>
            <div class="input-group rounded w-25 mb-3">
                <div>
                    <!-- <div class="" id="distributedId"></div> -->
                    <!-- <input type="hidden" id="distributedId" value=""> -->
                    <input type="text" class="form-control" id="damagesearchInput" placeholder="Search...">
                    <!-- <div id="searchResults"></div> -->
                </div>
            </div>

            <div id="show_Product">
                <table class="table table-bordered text-center shadow rounded detable" id='ds_itemTable'>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 30%;">Product Name</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Point</th>
                            <th scope="col">Ticket</th>
                            <th scope="col">Kyat</th>
                            <th scope="col">Purchased Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total Amount</th>
                            <th class="col">Reason</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>  

        {!! Form::close() !!}
        <div class="text-center my-5">
            <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="damageForm" class="btn btn-blue ms-2" id="demagebutton">Save</button>
        </div>
    </div>
@endsection
