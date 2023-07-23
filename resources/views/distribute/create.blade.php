@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Distribute</h4>
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

        {{-- @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        
        <div class="errorbox"></div>

        {!! Form::open([
            'route' => 'distribute.store',
            'method' => 'post',
            'class' => '',
            'id' => 'distributeForm',
        ]) !!}
            @csrf
            <div class="p-4 rounded border shadow-sm mb-5">
                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        {!! Form::label('date', 'Date', ['class' => 'form-label']) !!}
                        {!! Form::date('date', null, ['class' => 'form-control', 'id' => 'date']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('reference_No', 'Reference No.', ['class' => 'form-label']) !!}
                        {!! Form::text('reference_No', $generatedRef, ['class' => 'form-control', 'id' => 'reference', 'readonly' => 'readonly']) !!}
                    </div>
                    
                    <div class="col-md-4">
                        {!! Form::label('from_outlet', 'From (Outlet)', ['class' => 'form-label']) !!}
                        {!! Form::select('from_outlet', $outlets, null, [
                            'placeholder' => 'Choose From outlets',
                            'class' => 'form-control',
                            'id' => 'fromOutlet',
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('to_outlet', 'To (Outlet)', ['class' => 'form-label']) !!}
                        {!! Form::select('to_outlet', $outlets, null, [
                            'placeholder' => 'Choose to outlets',
                            'class' => 'form-control',
                            'id' => 'toOutlet',
                        ]) !!}
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-4">Add Products</h5>
            <div class="input-group rounded w-25 mb-3">
                <div>
                    <!-- <div class="" id="distributedId"></div> -->
                    <!-- <input type="hidden" id="distributedId" value=""> -->
                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    <!-- <div id="searchResults"></div> -->
                </div>
            </div>

            <div id="show_dsProduct">
                <table class="table table-bordered text-center shadow rounded" id='itemTable'>
                    <thead>
                        <tr>
                            <th scope="col" style="width: 30%;">Product Name</th>
                            <th scope="col">Item Code</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Purchased Price</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-4 px-4 justify-content-end my-5">
                <div class="">
                    <!-- <label for="remark" class="d-block mb-2">Remark</label>
                            <textarea name="remark" id="" cols="40" rows="4"></textarea> -->
                    {!! Form::label('remark', 'Remark', ['class' => 'form-label']) !!}
                    {!! Form::textarea('remark', null, [
                        'class' => 'form-control',
                        'id' => 'remark',
                        'cols' => '40',
                        'rows' => '4',
                    ]) !!}
                </div>
                <div class="align-items-center d-flex">
                    <h4 class="fw-bolder">Total Amount: <span id="total"
                            class="ms-3 inline-block text-blue">0</span></h4>
                </div>
            </div>
        {!! Form::close() !!}

        <div class="text-center my-5">
            <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="distributeForm" class="btn btn-blue ms-2" id="dsbutton">Next</button>
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
                <p class="text-danger">Are you sure delete this Item</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary confirmButton">Confirm</button>
            </div>
            </div>
        </div>
    </div>

@endsection
