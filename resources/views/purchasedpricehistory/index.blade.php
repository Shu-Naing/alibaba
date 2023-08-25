@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Purchased Price History</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>

        @php
            $from_date = session()->get(PURCHASEEDPRICEHISTORY_FROMDATE_FILTER);
            $to_date = session()->get(PURCHASEEDPRICEHISTORY_TODATE_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'search-list-purchasedpricehistory',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
            ]) !!}  
            @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    {!! Form::label('fromDate', 'From Date', ['class' => 'form-label']) !!}
                    {{ Form::date('fromDate', $from_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-4">
                    {!! Form::label('toDate', 'To Date', ['class' => 'form-label']) !!}
                    {{ Form::date('toDate', $to_date, ['class' => 'form-control']) }}
                </div>
                
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('purchasedpricehistory-search-reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('purchased-price-history.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}
       
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Ticket</th>
                    <th>Point</th>
                    <th>Kyat</th>
                    <th>Purchased Price</th>
                    <th>Quantity</th>
                    <th>GRN No</th>
                    <th>Purchase Date</th>
                    <th>Etry Date</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($purchased_price_histories as $purchased_price_history)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $purchased_price_history->variation->item_code }}</td>
                    <td>{{ $purchased_price_history->tickets }}</td>
                    <td>{{ $purchased_price_history->points }}</td>
                    <td>{{ $purchased_price_history->kyat }}</td>
                    <td>{{ $purchased_price_history->purchased_price }}</td>
                    <td>{{ $purchased_price_history->quantity }}</td>
                    <td>{{ $purchased_price_history->grn_no }}</td>
                    <td>{{ $purchased_price_history->received_date }}</td>
                    <td>{{ date('d-m-y',strtotime($purchased_price_history->created_at)) }}</td>
               </tr>
                @endforeach 
            </tbody>
        </table>
    </div>
@endsection
