@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@php
    $outlet = session()->get(OUTLET_LEVEL_OVERVIEW_FILTER);
    $from_date = session()->get(OUTLET_LEVEL_OVERVIEW_FROM_DATE_FILTER);
    $to_date = session()->get(OUTLET_LEVEL_OVERVIEW_TO_DATE_FILTER);
@endphp

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Outlet Stock Overview Report(Store)</h4>
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

        {!! Form::open([
            'route' => 'outletleveloverview.search',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-2">
                    {!! Form::label('fromDate', 'From Date', ['class' => 'form-label']) !!}
                    {{ Form::date('fromDate', $from_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('toDate', 'To Date', ['class' => 'form-label']) !!}
                    {{ Form::date('toDate', $to_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-3">
                    {!! Form::label('outlet_id', 'Outlet', ['class' => 'form-label']) !!}
                    {{ Form::select('outlet_id', $outlets, $outlet, ['placeholder'=>'Choose ','class' => 'form-control']) }}
    
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('outletleveloverview.reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('outletleveloverview.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}

        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Outlet</th>
                    <th>Item Code</th>
                    <th>Image</th>
                    <th>Size</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Point</th>
                    <th>Ticket</th>
                    <th>Kyat</th>
                    <th>Purchase Price</th>
                    <th>Opening Qty</th>
                    <th>Received Qty</th>
                    <th>Issued Qty</th>
                    <th>Balance</th>
                    <th>Check</th>
                    <th>Physical Qty</th>
                    <th>Difference Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($outletleveloverview as $outlevel)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{ date('Y-M',strtotime($outlevel->date)) }}</td>
                        <td>{{ $outlevel->name }}</td>
                        <td>{{ $outlevel->item_code }}</td>
                        <td> <img src = "{{asset('storage/' . $outlevel->image)}}" alt="images"/></td>                        
                        <td>{{ isset($size_variants[$outlevel->size_variant_value]) ? $size_variants[$outlevel->size_variant_value] : ''}}
                        <td>{{ isset($units[$outlevel->unit_id]) ? $units[$outlevel->unit_id] : ''}}
                        <td>{{ isset($categories[$outlevel->category_id]) ? $categories[$outlevel->category_id] : ''}}
                        <td>{{ $outlevel->points }}</td>
                        <td>{{ $outlevel->tickets }}</td>
                        <td>{{ $outlevel->kyat }}</td>
                        <td>{{ $outlevel->purchased_price }}</td>
                        <td>{{ $outlevel->opening_qty }}</td>
                        <td>{{ $outlevel->receive_qty }}</td>
                        <td>{{ $outlevel->issued_qty }}</td>
                        <td class="outlevel-balance-qty">{{ $outlevel->opening_qty + $outlevel->receive_qty - $outlevel->issued_qty }}</td>
                        <td><input class="form-check-input mt-0 outletleveloverview-check" type="checkbox" value="{{ $outlevel->id }}" aria-label="Checkbox for following text input" {{ ($outlevel->is_check == 1) ? 'checked' : '' }} /></td>
                        <td><input class="form-control mt-0 physical-num no-spin-buttons outlevel-physical-qty" type="number" min='0' value="{{ $outlevel->physical_qty }}" data-id="{{ $outlevel->id }}" /></td>
                        <td>{{ $outlevel->difference_qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="" style="margin-bottom: 200px;"></div>
    </div>
@endsection
