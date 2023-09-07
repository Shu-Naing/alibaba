@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Damages</h4>
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
            $from_date = session()->get(DA_FROMDATE_FILTER);
            $to_date = session()->get(DA_TODATE_FILTER);
            $outlet_id = session()->get(DA_OUTLETID_FILTER);
            $damage_no = session()->get(DA_DAMAGE_FILTER);
            $itemCode = session()->get(DA_ITEMCODE_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'search-list-damage',
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
                    {!! Form::label('damage_no', 'Damage No', ['class' => 'form-label']) !!}
                    {{ Form::text('damage_no', $damage_no, ['class' => 'form-control']) }}
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
                    <a href="{{route('damage-search-reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <!-- <a href="{{ route('distribute-detail-export') }}" class="btn btn-blue ms-2">Export to Excel</a> -->
                </div>
            </div>
        {!! Form::close() !!}

        <div class="d-flex mb-3 justify-content-end">            
            <a class="btn btn-blue" href="{{ route('damage.create') }}">Add +</a>
            <a href="{{ route('damage.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Month</th>
                    <th>Date</th>
                    <th>Damage No</th>
                    <th>Location</th>
                    <th>Product Code</th>
                    <th>Point</th>
                    <th>Ticket</th>
                    <th>Kyat</th>
                    <th>Purchase Price</th>
                    <th>Qty</th>
                    <th>Total Amount</th>
                    <th>Reason</th>
                    <th>Name</th>
                    <th>Compensation Amount</th>
                    <th>Action</th>
                    <th>Error</th>
                    <th>Distination</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($damages as $damage)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ date("M",strtotime($damage->date)) }}</td>
                        <td>{{ $damage->date }}</td>
                        <td>{{ $damage->damage_no }}</td>
                        <td>{{ $outlets[$damage->outlet_id] }}</td>
                        <td>{{ $damage->item_code }}</td>
                        <td>{{ $damage->point }}</td>
                        <td>{{ $damage->ticket }}</td>
                        <td>{{ $damage->kyat }}</td>
                        <td>{{ $damage->purchase_price }}</td>
                        <td>{{ $damage->quantity }}</td>
                        <td>{{ $damage->total }}</td>
                        <td>{{ $damage->reason }}</td>
                        <td>{{ $damage->name }}</td>
                        <td>{{ $damage->amount }}</td>
                        <td>{{ $action[$damage->action] }}</td>
                        <td>{{ $damage->error }}</td>
                        <td>{{ $distination[$damage->distination] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
