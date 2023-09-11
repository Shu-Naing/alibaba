@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Price Changed History</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
{{-- 
        @php
            $outlet_id = session()->get(OUTLET_LEVEL_HISTORY_FILTER);
        @endphp --}}

        {!! Form::open([
            'route' => 'price-changed-history.search',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-3">
                    {!! Form::label('item_code', 'Item Code', ['class' => 'form-label']) !!}
                    {{ Form::text('item_code', null, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-3">
                    {!! Form::label('received_date', 'Received Date', ['class' => 'form-label']) !!}
                    {{ Form::date('received_date', null, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('price-changed-history.reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('price-changed-history.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}

        <table id="table_id">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Item Code</th>
                    <th>Purchased Price</th>
                    <th>Point</th>
                    <th>Ticket</th>
                    <th>Kyat</th>
                    <th>Received Date</th>                    
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($price_changed_histories as $price_changed_history)                                  
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{ $price_changed_history->item_code}}</td> 
                        <td>{{ $price_changed_history->purchased_price }}</td>
                        <td>{{ $price_changed_history->points }}</td>
                        <td>{{ $price_changed_history->tickets }}</td>
                        <td>{{ $price_changed_history->kyat }}</td>
                        <td>{{ $price_changed_history->received_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
