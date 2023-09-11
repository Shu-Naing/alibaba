@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Sell</h4>
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
            $invoice_id = session()->get(SELL_INVOICE_FILTER); 
            $payment_type = session()->get(SELL_PAYMENTTYPE_FILTER); 
            $from_date = session()->get(SELL_FROMDATE_FILTER); 
            $to_date = session()->get(SELL_TODATE_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'sell-search',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
            ]) !!}  
            @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-2">
                    {!! Form::label('from_date', 'From Date', ['class' => 'form-label']) !!}
                    {{ Form::date('from_date', $from_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('to_date', 'To Date', ['class' => 'form-label']) !!}
                    {{ Form::date('to_date', $to_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('invoice_id', 'Invoice ID', ['class' => 'form-label']) !!}
                    {!! Form::text('invoice_id',$invoice_id, ['placeholder'=>'Enter Invoice','class' => 'form-control']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('payment_type', 'Payment Type', ['class' => 'form-label']) !!}
                    {!! Form::select('payment_type', $payment_types, $payment_type, ['placeholder'=>'Choose..','class' => 'form-control']) !!}
                </div>               
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('sell-search-reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('sell-export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Invoice No</th>
                    <th>Total</th>
                    <th>Payment Type</th>
                    <th>Invoice Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($posSellLists as $posSellList)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $posSellList->invoice_no }}</td>
                        <td>{{ $posSellList->total }}</td>
                        <td>{{ $posSellList->payment_type }}</td>
                        <td>{{ $posSellList->created_at}}</td>
                        <td><a href="{{ route('sell.show',$posSellList->id) }}" class="mx-2">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
