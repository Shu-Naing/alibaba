@extends('layouts.navbar')
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

        {!! Form::open(array('route' => 'distribute.store', 'method' => 'post', 'id' => 'myForm')) !!}
            @csrf
            <div class="p-4 rounded border shadow-sm mb-5">
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
                    </div>
                </div>
            </div>
            <div id="formFieldsContainer"></div>
        {!! Form::close() !!}

        <div class="text-center my-5">
            <button type="button" onclick="addField()" class="btn btn-primary">Add Field</button>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="myForm" class="btn btn-blue ms-2">Next</button>
        </div>
    </div>
    

@endsection
