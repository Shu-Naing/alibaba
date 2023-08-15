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

        {!! Form::open([
            'route' => 'damage.store',
            'method' => 'post',
            'class' => 'px-3 mb-5',
        ]) !!}
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-6 col-sm-6">
                    {{ Form::label('date', 'Date *', ['class' => 'form-label' . ($errors->has('date') ? ' text-danger' : '')]) }}
                    {{ Form::date('date', null, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'id' => 'date']) }}
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {{ Form::label('voucher_no', 'Voucher No *', ['class' => 'form-label' . ($errors->has('voucher_no') ? ' text-danger' : '')]) }}
                    {{ Form::text('voucher_no', null, ['class' => 'form-control' . ($errors->has('voucher_no') ? ' is-invalid' : ''), 'id' => 'voucher_no']) }}
                    @error('voucher_no')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('outlet_id', 'Outlet Id', array('class' => 'form-label'. ($errors->has('outlet_id') ? ' text-danger' : ''))) !!}
                    {!! Form::select('outlet_id', $outlets, null, array('placeholder' => 'Choose From outlets', 'class' => 'form-control' . ($errors->has('outlet_id') ? ' is-invalid' : ''),'id'=>'open_outlet_id')) !!}
                    @error('outlet_id')
                        <span class="text-danger">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('item_code', 'Product Code', array('class' => 'form-label'.($errors->has('item_code') ? ' text-danger' : ''))) !!}
                    {{ Form::text('item_code', null, ['class' => 'form-control' . ($errors->has('item_code') ? ' is-invalid' : ''), 'id' => 'item_code']) }}
                    @error('item_code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12 col-sm-6">
                    {!! Form::label('description ', 'Description', ['class' => 'form-label']) !!}
                    {!! Form::textarea('description', null, [
                        'class' => 'form-control',
                        'id' => 'description ',
                        'cols' => '40',
                        'rows' => '4',
                    ]) !!} 
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('quantity', 'Damage Quantity *', ['class' => 'form-label'.($errors->has('quantity') ? ' text-danger' : '')]) !!}
                    {!! Form::number('quantity', null, ['class' => 'form-control'. ($errors->has('quantity') ? ' is-invalid' : ''), 'id' => 'quantity']) !!}
                    @error('quantity')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('ticket ', 'Ticket *', ['class' => 'form-label'.($errors->has('ticket') ? ' text-danger' : '')]) !!}
                    {!! Form::number('ticket', null, ['class' => 'form-control'. ($errors->has('ticket') ? ' is-invalid' : ''), 'id' => 'ticket']) !!}
                    @error('ticket')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('original_cost ', 'Original Cost *', ['class' => 'form-label'.($errors->has('original_cost') ? ' text-danger' : '')]) !!}
                    {!! Form::number('original_cost', null, ['class' => 'form-control'. ($errors->has('original_cost') ? ' is-invalid' : ''), 'id' => 'original_cost']) !!}
                    @error('original_cost')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('amount_ks ', 'Amount (ks) *', ['class' => 'form-label'.($errors->has('amount_ks') ? ' text-danger' : '')]) !!}
                    {!! Form::number('amount_ks', null, ['class' => 'form-control'. ($errors->has('amount_ks') ? ' is-invalid' : ''), 'id' => 'amount_ks']) !!}
                    @error('amount_ks')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12 col-sm-6">
                    {!! Form::label('reason', 'Reason', ['class' => 'form-label']) !!}
                    {!! Form::textarea('reason', null, [
                        'class' => 'form-control',
                        'id' => 'reason',
                        'cols' => '40',
                        'rows' => '4',
                    ]) !!} 
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('name', 'Name', array('class' => 'form-label'.($errors->has('name') ? ' text-danger' : ''))) !!}
                    {{ Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name']) }}
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('amount', 'Compensation Amount *', ['class' => 'form-label'.($errors->has('amount') ? ' text-danger' : '')]) !!}
                    {!! Form::number('amount', null, ['class' => 'form-control'. ($errors->has('amount') ? ' is-invalid' : ''), 'id' => 'amount']) !!}
                    @error('amount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12 col-sm-6">
                    {!! Form::label('error', 'Error *', ['class' => 'form-label'.($errors->has('error') ? ' text-danger' : '')]) !!}
                    {!! Form::text('error', null, ['class' => 'form-control'. ($errors->has('error') ? ' is-invalid' : ''), 'id' => 'error']) !!}
                    @error('error')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('action', 'Action', array('class' => 'form-label'. ($errors->has('action') ? ' text-danger' : ''))) !!}
                    {!! Form::select('action', $action, null, array('placeholder' => 'Choose From outlets', 'class' => 'form-control' . ($errors->has('action') ? ' is-invalid' : ''),'id'=>'open_action')) !!}
                    @error('action')
                        <span class="text-danger">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('distination', 'Distination', array('class' => 'form-label'. ($errors->has('distination') ? ' text-danger' : ''))) !!}
                    {!! Form::select('distination', $distination, null, array('placeholder' => 'Choose From outlets', 'class' => 'form-control' . ($errors->has('distination') ? ' is-invalid' : ''),'id'=>'open_distination')) !!}
                    @error('distination')
                        <span class="text-danger">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('damage_no', 'Damage No', array('class' => 'form-label'.($errors->has('damage_no') ? ' text-danger' : ''))) !!}
                    {{ Form::text('damage_no', null, ['class' => 'form-control' . ($errors->has('damage_no') ? ' is-invalid' : ''), 'id' => 'damage_no']) }}
                    @error('damage_no')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('column1', 'Column1', array('class' => 'form-label'.($errors->has('column1') ? ' text-danger' : ''))) !!}
                    {{ Form::text('column1', null, ['class' => 'form-control' . ($errors->has('column1') ? ' is-invalid' : ''), 'id' => 'column1']) }}
                    @error('column1')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="">
                <a class="btn btn-red" href="{{ URL::current() }}">Cancel</a>
                <button type="submit" class="btn btn-blue ms-2">Save</button>
            </div>
        {!! Form::close() !!}

        
    </div>
@endsection
