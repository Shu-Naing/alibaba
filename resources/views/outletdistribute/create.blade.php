@extends('layouts.navbar')
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
        {!! Form::open(array('route' => 'outletdistribute.store', 'method' => 'post', 'class' => 'p-4 rounded border shadow-sm mb-5', 'id' => 'outletdistribute')) !!}
            @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    {!! Form::label('date', 'Date', array('class' => 'form-label')) !!}
                    {!! Form::date('date', null, array('class' => 'form-control', 'id'=>'date')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('reference_No', 'Reference No.', array('class' => 'form-label')) !!}
                    {!! Form::text('reference_No', null, array('class' => 'form-control', 'id' => 'reference')) !!}
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
                    {!! Form::label('counterMachine', 'To (Counter / Machine)', array('class' => 'form-label')) !!}
                    {!! Form::select('counterMachine', $counter_machine, null, array('placeholder' => 'Choose', 'class' => 'form-control counterMachine','id'=>'counterMachine')) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('counter', 'Counter', array('class' => 'form-label')) !!}
                    {!! Form::select('to_counter', $counter, null, array('placeholder' => 'Choose', 'class' => 'form-control counter','id'=>'counter', 'disabled')) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('machine', 'Machine', array('class' => 'form-label')) !!}
                    {!! Form::select('to_machine', $machines, null, array('placeholder' => 'Choose', 'class' => 'form-control machine','id'=>'machine', 'disabled')) !!}
                </div>
            </div>
        {!! Form::close() !!}

        <div class="text-center my-5">
            <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="outletdistribute" class="btn btn-blue ms-2">Next</button>
        </div>
    </div>
    

@endsection
