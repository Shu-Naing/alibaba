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

            <div id="errorItem"></div>  

            <div class="row p-4">
                <div class="col col-sm-3 col-lg-2 fw-bold">Date: <span class="text-danger">{{$distribute['distribute']->date}}</span></div>
                <div class="col col-sm-3 col-lg-3 fw-bold">Reference No: <span class="text-danger">{{$distribute['distribute']->reference_No}}</span></div>
                <div class="col col-sm-3 col-lg-3 fw-bold">From Outlet: <span class="text-danger">{{get_outlet_name($distribute['distribute']->from_outlet)}}</span></div>
                <div class="col col-sm-3 col-lg-2 fw-bold">To Outlet: <span class="text-danger">{{get_outlet_name($distribute['distribute']->to_outlet)}}</span></div>
                <div class="col col-sm-3 col-lg-2 fw-bold">Prepared By: <span class="text-danger">{{$users[$distribute['distribute']->created_by]}}</span></div>
            </div>
            <table class="table table-bordered text-center shadow rounded">
                <thead>
                    <tr>
                        <th scope="col">Item Code</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Size Variant</th>
                        <th scope="col">Purchase Price:</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $quantitySum = 0;
                        $subtotalSum = 0;
                    @endphp

                    @foreach($distribute['distribute_products_data'] as $distribute_product)
                        <tr>
                            <td>{{$distribute_product->item_code}}</td>
                            <td><img class="product-img" src="{{ asset('storage/' . $distribute_product->image) }}" alt="{{ $distribute_product->image }}"></td>
                            <td>{{$distribute_product->value}}</td>
                            <td>{{ number_format($distribute_product->purchased_price, 0, '', ',') }}</td>                           
                            <td style="width: 150px;">{!! Form::text('reference_No', $distribute_product->quantity, ['class' => 'form-control text-center', 'id' => 'disprod_quantity', 'disabled' => $distribute['distribute']->status == 2 ? 'disabled' : null, 'data-id' => $distribute_product->id, ]) !!}</td>
                            <td>{{ number_format($distribute_product->subtotal, 0, '', ',') }} </td>
                        </tr>

                        @php
                            $quantitySum += $distribute_product->quantity;
                            $subtotalSum += $distribute_product->subtotal;
                        @endphp

                    @endforeach   
                        <tr>                          
                            <td colspan="4" class="text-end">Total</td>
                            <td>{{$quantitySum}}</td>
                            <td>{{ number_format($subtotalSum, 0, '', ',') }}</td>
                        </tr>                 
                </tbody>
            </table>

            <div class="mr-0 my-5">
                <a class="btn btn-blue" href="{{ route('distribute.index') }}">Back</a>
                <button id="approvept" class="btn btn-success" onclick="updateStatus('approve','<?php echo $distribute['distribute']->id ?>');" @if($distribute['distribute']->status == 2) disabled @endif>Approve</button>
                <button id="rejectpt" class="btn btn-danger" onclick="updateStatus('reject',<?php echo $distribute['distribute']->id ?>);"  @if($distribute['distribute']->status == 2) disabled @endif>Reject</button>
                
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
                
                console.log(response.errorItem);
                // window.location.reload();
                if(response.errorItem) {
                    $.each(response.errorItem, function (key, value) {
                        $("#errorItem").append(
                            '<div class="alert alert-warning">' + value + ' is out of stock </div>'
                        );
                    });
                }

                document.getElementById('approvept').disabled = true;
                document.getElementById('rejectpt').disabled = true;
              

            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
               
            }
        });   
          
    }
</script>
@endsection


