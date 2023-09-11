@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Adjustments</h4>
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
            $from_date = session()->get(ADJ_FROMDATE_FILTER);
            $to_date = session()->get(ADJ_TODATE_FILTER);
            $outlet_id = session()->get(ADJ_OUTLETID_FILTER);
            $adjNo = session()->get(ADJ_ADJNO_FILTER);
            $itemCode = session()->get(ADJ_ITEMCODE_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'search-list-adjustment',
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
                <div class="col-md-2">
                    {!! Form::label('adjNo', 'Adj No', ['class' => 'form-label']) !!}
                    {{ Form::text('adjNo', $adjNo, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('outletId', 'Location', ['class' => 'form-label']) !!}
                    {!! Form::select('outletId',$outlets, $outlet_id, ['placeholder'=>'Choose..','class' => 'form-control', 'id' => 'outletId']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('itemCode', 'Product Code', ['class' => 'form-label']) !!}
                    {{ Form::text('itemCode', $itemCode, ['class' => 'form-control']) }}
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('adjustment-search-reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <!-- <a href="{{ route('distribute-detail-export') }}" class="btn btn-blue ms-2">Export to Excel</a> -->
                </div>
            </div>
        {!! Form::close() !!}

        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('adjustment.create') }}">Add +</a>
            <a href="{{ route('adjustment.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Adj No</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Product Code</th>
                    <th>Qty</th>
                    <th>Type</th>
                    <th>Remark</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($adjustments as $adjustment)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $adjustment->adj_no }}</td>
                        <td>{{ $adjustment->date }}</td>
                        <td>{{ get_outlet_name($adjustment->outlet_id) }}</td>
                        <td>{{ $adjustment->item_code }}</td>
                        <td>{{ $adjustment->adjustment_qty }}</td>
                        <td>{{ $adjustment_types[$adjustment->type] }}</td>
                        <td>{{ $adjustment->remark }}</td>
                        <td>{{ $adjustment_types[$adjustment->type] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
