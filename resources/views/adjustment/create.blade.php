@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create Adjustment</h4>
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
            'route' => 'adjustment.store',
            'method' => 'post',
            'class' => 'px-3 mb-5',
        ]) !!}
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('outlet_id', 'Outlet Id *', array('class' => 'form-label'. ($errors->has('outlet_id') ? ' text-danger' : ''))) !!}
                    {!! Form::select('outlet_id', $outlets, null, array('placeholder' => 'Choose From outlets', 'class' => 'form-control' . ($errors->has('outlet_id') ? ' is-invalid' : ''),'id'=>'open_outlet_id')) !!}
                    @error('outlet_id')
                        <span class="text-danger">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {{ Form::label('adj_no', 'Adj No *', ['class' => 'form-label' . ($errors->has('adj_no') ? ' text-danger' : '')]) }}
                    {{ Form::text('adj_no', null, ['class' => 'form-control' . ($errors->has('adj_no') ? ' is-invalid' : ''), 'id' => 'adj_no', 'placeholder' => 'Adjustment No', 'readonly']) }}
                    @error('adj_no')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {{ Form::label('date', 'Date *', ['class' => 'form-label' . ($errors->has('date') ? ' text-danger' : '')]) }}
                    {{ Form::date('date', null, ['class' => 'form-control' . ($errors->has('date') ? ' is-invalid' : ''), 'id' => 'date', 'placeholder' => 'Name']) }}
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="col-md-6 col-sm-6 position-relative">
                    {!! Form::label('item_code', 'Product Code', array('class' => 'form-label'.($errors->has('item_code') ? ' text-danger' : ''))) !!}
                    {{ Form::text('item_code', null, ['class' => 'form-control' . ($errors->has('item_code') ? ' is-invalid' : ''), 'id' => 'item_code', 'placeholder' => 'Product Code']) }}
                    @error('item_code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('type', 'Type', array('class' => 'form-label'. ($errors->has('type') ? ' text-danger' : ''))) !!}
                    {!! Form::select('type', $adjustment_types, null, array('placeholder' => 'Choose', 'class' => 'form-control' . ($errors->has('type') ? ' is-invalid' : ''),'id'=>'type')) !!}
                    @error('type')
                        <span class="text-danger">{{ $message }}</span> 
                    @enderror
                </div>
                <div class="col-md-6 col-sm-6">
                    {!! Form::label('adjustment_qty', 'Adjustment Quantity *', ['class' => 'form-label'.($errors->has('adjustment_qty') ? ' text-danger' : '')]) !!}
                    {!! Form::number('adjustment_qty', null, ['class' => 'form-control'. ($errors->has('adjustment_qty') ? ' is-invalid' : ''), 'id' => 'adjustment_qty']) !!}
                    @error('adjustment_qty')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12 col-sm-6">
                    {!! Form::label('remark', 'Remark', ['class' => 'form-label']) !!}
                    {!! Form::textarea('remark', null, [
                        'class' => 'form-control',
                        'id' => 'remark',
                        'cols' => '40',
                        'rows' => '4',
                    ]) !!}
                    
                </div>
            </div>

            <div class="">
                <a class="btn btn-red" href="{{ URL::current() }}">Cancel</a>
                <button type="submit" class="btn btn-blue ms-2">Save</button>
            </div>
        {!! Form::close() !!}

        
    </div>
@endsection
