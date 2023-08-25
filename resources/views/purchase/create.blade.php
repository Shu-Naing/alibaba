@extends('layouts.app')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Purchase</h4>
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

        @if(Session::has('errorPurchase'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('errorPurchase') }}
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

        <div class="py-3">
            {!! Form::open(array('route' => 'purchaseAdd.import', 'method' => 'post', 'enctype' => 'multipart/form-data')) !!}
                @csrf
                <div class="row">
                    <div class="col-lg-4 col-sm-12">
                        {!! Form::file('file', array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <button class="btn btn-primary">Submit</button>
                        <!-- <a href="{{ route('product.sample-export') }}" class="btn btn-success">Download Template</a> -->
                        <a href="{{ route('purchaseAdd.sample-export') }}" class="btn btn-success">Download Template</a>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>

        {!! Form::open([
            'route' => 'purchase.store',
            'method' => 'POST',
            'enctype' => 'multipart/form-data',
            'id' => 'purchaseForm',
        ]) !!}
            
            <div class="p-4 rounded border shadow-sm mb-5">
                <div class="row mb-3 g-3">

                    <div class="col-md-4">
                        {!! Form::label('grn_no', 'GRN No', ['class' => 'form-label']) !!}
                        {!! Form::text('grn_no', null, ['class' => 'form-control', 'id' => 'grn_no']) !!}
                    </div>
                    
                    <div class="col-md-4">
                        {!! Form::label('received_date', 'Received Date', ['class' => 'form-label']) !!}
                        {!! Form::date('received_date', null, ['class' => 'form-control', 'id' => 'received_date']) !!}
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('country', 'Country', ['class' => 'form-label']) !!}
                        {!! Form::select('country', $countries, null, [
                            'placeholder' => 'Choose...',
                            'class' => 'form-control',
                            'id' => 'country',
                        ]) !!}
                    </div>

                </div>
            </div>

            <h5 class="fw-bold mb-2">Add Item</h5>
            <div class="input-group rounded w-25 mb-3">
                <div>
                    <input type="text" class="form-control" id="searchInputPurchase" placeholder="Search...">
                </div>
            </div>

            <div id="show_Product">
                <table class="table table-bordered text-center shadow rounded purchase_itemTable" id='purchase_itemTable'>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 30%;">Item Code</th>
                            <th scope="col">Ticket</th>
                            <th scope="col">Point</th>
                            <th scope="col">Kyat</th>
                            <th scope="col">Purchased Price</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        {{ Form::close() }}

        <div class="text-center my-5">
            <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="purchaseForm" class="btn btn-blue ms-2" id="purchasebutton">Save</button>
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
                <p class="text-danger">Are you sure delete this Item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-muted text-white" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn delmodal-comfirm-btn cs-bg-primary text-white confirmButton">Confirm</button>
            </div>
            </div>
        </div>
    </div>
    

@endsection
