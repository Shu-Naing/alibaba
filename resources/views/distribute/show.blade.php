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
                <div class="col col-sm-3 col-lg-3 fw-bold">Date: <span class="text-danger">{{$distribute['distribute']->id}}</span></div>
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
                    @foreach($distribute['distribute_products_data'] as $distribute_product)
                        <tr>
                            <td>{{$distribute_product->item_code}}</td>
                            <td><img class="product-img" src="{{ asset('storage/' . $distribute_product->image) }}" alt="{{ $distribute_product->image }}"></td>
                            <td>{{$distribute_product->value}}</td>
                            <td>{{$distribute_product->purchased_price}}</td>                           
                            <td>{{$distribute_product->quantity}}</td>
                            <td>{{$distribute_product->subtotal}}</td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>

            <div class="mr-0 my-5">
                <a class="btn btn-blue" href="{{ url()->previous() }}">Back</a>
                <button class="btn btn-success" onclick="updateStatus('approve','<?php echo $distribute['distribute']->id ?>');" @if($distribute['distribute']->status == 2) disabled @endif>Approve</button>
                <button class="btn btn-danger" onclick="updateStatus('reject',<?php echo $distribute['distribute']->id ?>);"  @if($distribute['distribute']->status == 2) disabled @endif>Reject</button>
                
                <a class="btn btn-info" href="{{route('distribute.preview',$distribute['distribute']->id)}}">Preview</a>
            </div>
        </div>
@endsection
@section('scripts')
<script>
    function updateStatus(status,distribute_id){

        // console.log(di);
        $.ajax({
            type: "PUT",
            url: "/distribute/" + distribute_id,
            headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            data: {
                status : status,
            },
            success: function(response) {
                
                console.log(response);
                window.location.reload();
              

            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
               
            }
        });
          
    }
</script>
@endsection


