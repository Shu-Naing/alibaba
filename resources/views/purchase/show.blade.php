@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Purchase Item</h4>
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
            $itemcode = session()->get(PURCHASE_ITEMCODE_FILTER);
        @endphp
        
        {!! Form::open([
            'route' => 'search-purchase-detail',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    {!! Form::label('itemCode', 'Item Code', ['class' => 'form-label']) !!}
                    {!! Form::text('itemCode', $itemcode, ['class' => 'form-control', 'id' => 'itemCode']) !!}
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('purchase-search-reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('purchase-detail-export', $purchaseItems[0]->grn_no) }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}

        <div class="row p-4">
            <div class="col col-sm-3 col-lg-4 fw-bold d-flex align-items-center">GRN No: <span class="text-danger">{{$purchaseItems[0]->grn_no}}</span></div>
            <div class="col col-sm-3 col-lg-4 fw-bold d-flex align-items-center">Received Date: <span class="text-danger">{{$purchaseItems[0]->received_date}}</span></div>
            <div class="col col-sm-3 col-lg-4 fw-bold d-flex align-items-center">Country: {!! Form::select('country', $countries, $purchaseItems[0]->country, ['class' => 'form-control ms-2', 'id' => 'purchase-detail-country', 'data-id-grn' => $purchaseItems[0]->grn_no, 'data-id-received-date' => $purchaseItems[0]->received_date ]) !!}</div>
        </div>
        <table class="table table-bordered text-center shadow rounded">
            <thead>
                <tr>
                    <th scope="col">Item Code</th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Point</th>
                    <th scope="col">Kyat</th>
                    <th scope="col">Purchased Price</th>
                    <th scope="col">Qty</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($purchaseItems as $purchaseItem)
                    <tr>
                        <td>{{$purchaseItem->item_code}}</td>
                        <td>{{$purchaseItem->tickets}}</td>
                        <td>{{$purchaseItem->points}}</td>
                        <td>{{$purchaseItem->kyat}}</td>
                        <td>{{$purchaseItem->purchased_price}}</td>
                        <td>{{$purchaseItem->quantity}}</td>
                    </tr>

                @endforeach              
            </tbody>
        </table>
    </div>
@endsection


