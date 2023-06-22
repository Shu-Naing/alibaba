@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Distribute Product Details</h4>
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
                <div class="col col-sm-3 col-lg-3 fw-bold">Date: <span class="text-danger">{{$distribute['distribute']->date}}</span></div>
                <div class="col col-sm-3 col-lg-3 fw-bold">Reference No: <span class="text-danger">{{$distribute['distribute']->reference_No}}</span></div>
                <div class="col col-sm-3 col-lg-3 fw-bold">From Outlet: <span class="text-danger">{{$outlets[$distribute['distribute']->from_outlet]}}</span></div>
                <div class="col col-sm-3 col-lg-3 fw-bold">To Outlet: <span class="text-danger">{{$outlets[$distribute['distribute']->to_outlet]}}</span></div>
            </div>
            <table class="table table-bordered text-center shadow rounded">
                <thead>
                    <tr>
                        <th scope="col">Item Code</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Size</th>
                        <th scope="col">Purchase Price:</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distribute['distribute_products'] as $distribute)
                        <tr>
                            <td>{{$distribute->item_code}}</td>
                            <td><img class="product-img" src="{{ asset('storage/' . $distribute->image) }}" alt="{{ $distribute->image }}"></td>
                            <td>{{$distribute->value}}</td>
                            <td>{{$distribute->purchased_price}}</td>                           
                            <td>{{$distribute->quantity}}</td>
                            <td>{{$distribute->subtotal}}</td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
            <div class="mr-0 my-5">
                <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            </div>
        </div>
@endsection
