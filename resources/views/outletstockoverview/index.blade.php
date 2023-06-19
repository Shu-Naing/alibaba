@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create Outlet</h4>
            <div></div>
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
        {!! Form::open(array('route' => 'machine.store','method'=>'POST', 'class' => 'p-4 rounded border shadow-sm mb-5')) !!}
        <!-- <form class="p-4 rounded border shadow-sm mb-5" action="{{ route('machine.store') }}" method="post"> -->
            @csrf
            <div class="row mb-3 col-6">
                <div class="col-md-6">
                    {!! Form::label('outlet', 'Outlet', array('class' => 'form-label')) !!}
                    {!! Form::select('outlet', $outlets, null, array('placeholder' => 'Choose', 'class' => 'form-select outlet','id'=>'outlet')) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('machine', 'Location (Store or Machine)', array('class' => 'form-label')) !!}
                    {!! Form::select('machine', $machines, null, array('placeholder' => 'Choose', 'class' => 'form-select machine','id'=>'machine')) !!}
                </div>
            </div>
        <!-- </form> -->
        {!! Form::close() !!}

        <table id="table_id">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item Code</th>
                    <th>Opening Qty</th>
                    <th>Received Qty</th>
                    <th>Issued Qty</th>
                    <th>Machine Name</th>
                    <th>Check</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outletstocks as $outletstock)
                    <tr>
                        <td>{{ $outletstock->date }}</td>
                        <td>{{ $outletstock->item_code }}</td>
                        <td>{{ $outletstock->opening_qty }}</td>
                        <td>{{ $outletstock->received_qty }}</td>
                        <td>{{ $outletstock->issued_qty }}</td>
                        <td>{{ $outletstock->name }}</td>
                        <td>N/A</td>
                        <td>{{ $outletstock->opening_qty + $outletstock->received_qty - $outletstock->issued_qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="" style="margin-bottom: 200px;"></div>
    </div>
@endsection
