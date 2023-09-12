@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Demage Product Details and Edit</h4>
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
        @if (Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif

            <div id="errorItem"></div>  

            {!! Form::model($damages, [
            'method' => 'PATCH',
            'class' => 'd-flex justify-content-between w-100',
            'id' => 'damageForm',
            'route' => ['damage.update', $damages->id],
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
                            {{ Form::text('damage_no', null, ['class' => 'form-control' . ($errors->has('damage_no') ? ' is-invalid' : ''), 'id' => 'damage_no']) }}
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
                            {!! Form::text('action', null, array('class' => 'form-control' . ($errors->has('action') ? ' is-invalid' : ''),'id'=>'open_action')) !!}
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
                            {!! Form::text('distination', null, array('class' => 'form-control' . ($errors->has('distination') ? ' is-invalid' : ''),'id'=>'open_distination')) !!}
                            @error('distination')
                                <span class="text-danger">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}

            <table class="table table-bordered text-center shadow rounded">
                <thead>
                    <tr>
                        <!-- <th>Month</th>
                        <th>Date</th>
                        <th>Damage No</th>
                        <th>Location</th> -->
                        <th>Product Code</th>
                        <th>Point</th>
                        <th>Ticket</th>
                        <th>Kyat</th>
                        <th>Purchase Price</th>
                        <th>Qty</th>
                        <th>Total Amount</th>
                        <th>Reason</th>
                        <!-- <th>Name</th>
                        <th>Compensation Amount</th>
                        <th>Action</th>
                        <th>Error</th>
                        <th>Distination</th>
                        <th>Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($damage_items as $damage_item)
                        <tr>
                            <td>{{ $damage_item->item_code }}</td>
                            <td>{{ $damage_item->point }}</td>
                            <td>{{ $damage_item->ticket }}</td>
                            <td>{{ $damage_item->kyat }}</td>
                            <td>{{ $damage_item->purchase_price }}</td>
                            <td>{{ $damage_item->quantity }}</td>
                            <td>{{ $damage_item->total }}</td>
                            <td>{{ $damage_item->reason }}</td>
                        </tr>   
                    @endforeach             
                </tbody>
            </table>

            <div class="mr-0 my-5">
                <a class="btn btn-blue" href="{{ route('damage.index') }}">Back</a>
                <button type="submit" form="damageForm" class="btn btn-blue ms-2">Save</button>
            </div>
        </div>
@endsection


