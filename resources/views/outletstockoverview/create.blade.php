@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create Opening Stock Quantity</h4>
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

        <div class="card p-3">
            {!! Form::open(array('route' => 'outletstockoverview.import', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        {!! Form::file('file', array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <button class="btn btn-primary">Submit</button>
                        <!-- <a href="{{ route('product.sample-export') }}" class="btn btn-success">Download Template</a> -->
                        <a href="{{ route('outletstockoverview.sample-export') }}" class="btn btn-success">Download Template</a>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>

        {!! Form::open(['route' => 'outletstockoverview.store', 'method' => 'POST', 'class' => 'px-3']) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                {!! Form::label('date', 'Date *', ['class' => 'form-label']) !!}
                {!! Form::date('date', null, ['class' => 'form-control', 'id' => 'date']) !!}
            </div>
            <div class="col-md-4">
                    {!! Form::label('outlet_id', 'Outlet_id', array('class' => 'form-label')) !!}
                    {!! Form::hidden('outlet_id', $id) !!}
                    {!! Form::select('outlet_id_select',$outlets, $id, array('placeholder' => 'Choose From outlets', 'class' => 'form-control','id'=>'outlet_id', 'disabled' => 'disabled')) !!}
                </div>
            <div class="col-md-4">
                {!! Form::label('machine', 'Machine ID *', ['class' => 'form-label']) !!}
                {!! Form::select('machine_id', $machines['machine'], null, ['placeholder' => 'Choose', 'class' => 'form-control', 'id' => 'machine']) !!}
            </div>
            <!-- <div class="col-md-6">
                {!! Form::label('item_code', 'Item Code *', ['class' => 'form-label']) !!}
                {!! Form::select('item_code', [], null, ['class' => 'form-control', 'id' => 'item_code']) !!}
            </div> -->
            <!-- <div class="col-md-6">
                {!! Form::label('opening_qty', 'Opening Quantity *', ['class' => 'form-label']) !!}
                {!! Form::number('opening_qty', null, ['class' => 'form-control', 'id' => 'opening_qty']) !!}
            </div> -->
        </div>
        <div class="text-center">
            <!-- <a class="btn btn-red" href="{{ route('outlets.index') }}">Cancel</a> -->
            <button type="submit" class="btn btn-blue ms-2">Next</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection