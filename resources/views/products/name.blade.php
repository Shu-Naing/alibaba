@extends('layouts.navbar')
@section('cardtitle')
<h4>Role Management</h4>
@endsection
@section('cardbody')

<x-create-btn label="Create New Role" route="roles"/>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<table class="table table-bordered">
  <tr>
     <th>No</th>
     <th>Name</th>
     <th width="280px">Action</th>
  </tr>
    @foreac@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">List Product</h4>
            <div>
                @include('breadcrumbs')
            </div>
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

        {!! Form::open(array('route' => 'distribute.store', 'method' => 'post', 'class' => 'p-4 rounded border shadow-sm mb-5', 'id' => 'myForm')) !!}
            @csrf
            <div id="formFieldsContainer">
              <div class="form-group">
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
                        {!! Form::select('status', null, array('placeholder' => 'Choose to status', 'class' => 'form-control','id'=>'status')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('from_outlet', 'From (Outlet)', array('class' => 'form-label')) !!}
                        {!! Form::select('from_outlet', null, array('placeholder' => 'Choose From outlets', 'class' => 'form-control','id'=>'fromOutlet')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('to_outlet', 'To (Outlet)', array('class' => 'form-label')) !!}
                        {!! Form::select('to_outlet', null, array('placeholder' => 'Choose to outlets', 'class' => 'form-control','id'=>'toOutlet')) !!}
                    </div>
                </div>
              </div>
            </div>
        {!! Form::close() !!}

        <div class="text-center my-5">
            <button type="button" onclick="addField()" class="btn btn-primary">Add Field</button>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="myForm" class="btn btn-blue ms-2">Send</button>
        </div>
    </div>
    

@endsection


<form id="myForm">
        <div id="formFieldsContainer">
          <!-- Existing form fields -->
          <div class="form-group">
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="name[]" class="form-control" placeholder="Name">
                
                </div>
                <div class="col-md-4">
                    <input type="text" name="email[]" class="form-control" placeholder="Email">
                    
                </div>
                <div class="col-md-4">
                    <input type="file" name="image[]" class="form-control" accept="image/*">

                </div>
            </div>
          </div>
        </div>
        <button type="button" onclick="addField()" class="btn btn-primary">Add Field</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </form>