@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Outlet Stock History</h4>
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
            $outlet_id = session()->get(OUTLET_STOCK_HISTORY_OUTLET_FILTER);
            $machine_id = session()->get(OUTLET_STOCK_HISTORY_MACHINE_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'outletstockhistory.search',
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
                    <a href="{{route('outletstockhistory.reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('outletstockhistory.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
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
                    <th>No.</th>
                    <th>Machine</th>
                    <th>Date</th>
                    <th>Item Code</th>
                    <th>Quantity</th>
                    <th>Recieved/Issued</th>
                    <th>Branch</th>                    
                    <th>Remark</th>
                    <th>Check</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($histories as $history)                    
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{ $history->machine_name }}</td>  
                        <td>{{ $history->date }}</td>
                        <td>{{ $history->item_code }}</td>
                        <td>{{ $history->quantity }}</td>
                        <td>{{ isset($types[$history->type]) ? $types[$history->type] : ''  }}</td>
                        <td>{{ isset($branch[$history->branch]) ? $branch[$history->branch] : '' }}</td>
                        <td>{{ $history->remark }}</td>              
                        <td class="text-center"><input class="form-check-input mt-0 outletstockhistory-check" type="checkbox" value="{{ $history->id }}" aria-label="Checkbox for following text input" {{ ($history->is_check == 1) ? 'checked' : '' }} /></td>              
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
