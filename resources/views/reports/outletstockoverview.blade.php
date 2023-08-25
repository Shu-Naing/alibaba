@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Outlet Stock Overview Report</h4>
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
        @if (Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif

        @php
            $outlet_id = session()->get(OUTLET_STOCK_OVERVIEW_OUTLET_FILTER);
            $machine_id = session()->get(OUTLET_STOCK_OVERVIEW_MACHINE_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'outletstockoverview.search',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-3">
                    {!! Form::label('outlet_id', 'Outlet', ['class' => 'form-label']) !!}
                    {{ Form::select('outlet_id', $outlets, null, ['placeholder' => 'Choose...', 'class' => 'form-control', 'id' => 'outlet-dropdown']) }}
    
                </div>
                <div class="col-md-3">
                    {!! Form::label('machine_id', 'Machine', ['class' => 'form-label']) !!}
                    {{ Form::select('machine_id', $machines, null, ['placeholder' => 'Choose...', 'class' => 'form-control', 'id' => 'machine-dropdown']) }}
    
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('outletstockoverview.reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('outletstockoverview.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}

        <div class="row p-4">
            <div class="col col-sm-3 col-lg-2 fw-bold">Outlet: <span class="text-danger">{{ isset($outlets[$outlet_id]) ? $outlets[$outlet_id] : '' }}</span></div>
            <div class="col col-sm-3 col-lg-2 fw-bold">Machine: <span class="text-danger">{{ isset($machines[$machine_id]) ? $machines[$machine_id] : '' }}</span></div>
        </div>

        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Machine Name</th>
                    <th>Item Code</th>
                    <th>Opening Qty</th>
                    <th>Received Qty</th>
                    <th>Issued Qty</th>
                    <th>Balance Qty</th>
                    <th>Check</th>
                    <th>Physical Qty</th>
                    <th>Difference Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($outletstockoverviews as $outletstockoverview)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $outletstockoverview->name }}</td>
                        <td>{{ $outletstockoverview->item_code }}</td>
                        <td>{{ $outletstockoverview->opening_qty }}</td>
                        <td>{{ $outletstockoverview->receive_qty }}</td>
                        <td>{{ $outletstockoverview->issued_qty }}</td>
                        <td class="balance-qty">{{ ($outletstockoverview->opening_qty + $outletstockoverview->receive_qty) - $outletstockoverview->issued_qty }}</td>
                        <td><input class="form-check-input mt-0 outletstockoverview-check" type="checkbox" value="{{ $outletstockoverview->id }}" {{ ($outletstockoverview->is_check == 1) ? 'checked' : '' }} /></td>
                        <td><input class="form-control mt-0 physical-num no-spin-buttons physical-qty" type="number" min='0' value="{{ $outletstockoverview->physical_qty }}" data-id="{{ $outletstockoverview->id }}" /></td>
                        <td>{{ $outletstockoverview->difference_qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="" style="margin-bottom: 200px;"></div>
    </div>
@endsection
