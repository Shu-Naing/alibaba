@extends('layouts.app')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Outlet Issue</h4>
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
        {!! Form::open(array('route' => 'issue.store', 'method' => 'post', 'class' => 'p-4 rounded border shadow-sm mb-5', 'id' => 'outletissue')) !!}
            @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    {!! Form::label('date', 'Date', array('class' => 'form-label')) !!}
                    {!! Form::date('date', null, array('class' => 'form-control', 'id'=>'date')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('reference_No', 'Reference No.', array('class' => 'form-label')) !!}
                    {!! Form::text('reference_No', $generatedRef, array('class' => 'form-control', 'id' => 'reference')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('status', 'Status', array('class' => 'form-label')) !!}
                    {!! Form::select('status', $ds_status, null, array('placeholder' => 'Choose to status', 'class' => 'form-control','id'=>'status')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('from_outlet', 'From (Outlet)', array('class' => 'form-label')) !!}
                    {!! Form::hidden('from_outlet', $id) !!}
                    {!! Form::select('from_outlet_select',$outlets, $id, array('placeholder' => 'Choose From outlets', 'class' => 'form-control','id'=>'fromOutlet', 'disabled' => 'disabled')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('to_machine', 'From Machine', array('class' => 'form-label')) !!}
                    {!! Form::select('to_machine', $machines, null, array('placeholder' => 'Choose', 'class' => 'form-control','id'=>'to_machine')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('store_customer', 'To (Customer / Store)', array('class' => 'form-label')) !!}
                    {!! Form::select('store_customer', $branch, null, array('placeholder' => 'Choose', 'class' => 'form-control store_customer','id'=>'store_customer')) !!}
                </div>
            </div>
        {!! Form::close() !!}

        <div class="text-center my-5">
            <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="outletissue" class="btn btn-blue ms-2">Next</button>
        </div>
    </div>
    

@endsection
