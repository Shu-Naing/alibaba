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

        <div class="row p-4">
            <div class="col col-sm-3 col-lg-4 fw-bold d-flex align-items-center">Invoice No: <span class="text-danger ms-2"> {{$sell->invoice_no}}</span></div>
            <div class="col col-sm-3 col-lg-4 fw-bold d-flex align-items-center">Payment type: <span class="text-danger ms-2"> {{$sell->payment_type}}</span></div>
            <div class="col col-sm-3 col-lg-4 fw-bold d-flex align-items-center">Total: <span class="text-danger ms-2"> {{$sell->total}}</span></div>
        </div>
        <table class="table table-bordered text-center shadow rounded">
            <thead>
                <tr>
                    <th scope="col">Product Name</th>
                    <th scope="col">Item Code</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach($sellDetailLists as $sellDetailList)
                    <tr>
                        <td class="text-start w-25">{{$sellDetailList->product_name}}</td>
                        <td>{{$sellDetailList->item_code}}</td>
                        <td>{{$sellDetailList->quantity}}</td>
                        <td>{{$sellDetailList->variation_value}}</td>
                    </tr>

                @endforeach              
            </tbody>
        </table>
    </div>
@endsection


