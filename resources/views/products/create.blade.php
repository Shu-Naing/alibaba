@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Add Product</h4>
            <div>
            </div>
        </div>
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif
    <!-- Create a new product form -->  
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <input type="file" class="form-control">
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <button class="btn btn-primary">Submit</button>
                            <button class="btn btn-success">Download Template</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="item_code" class="form-label">Item Code</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <label for="product_id" class="form-label">Product ID</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="product_name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="sku" class="form-label">SKU *</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="unit" class="form-label">Unit *</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="received_date" class="form-label">Received Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-2 my-2">
                            <label for="alert_quantity" class="form-label">Alert Quantity</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-2 my-2 d-flex align-items-center">
                            <label class="form-check-label me-2" for="manage_stock">
                                Manage Stock
                                </label>
                            <input class="form-check-input" type="checkbox" value="" id="manage_stock"> 
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="received_qty" class="form-label">Received Qty</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="expired_date" class="form-label">Expired Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="product_image" class="form-label">Product Image</label>
                            <input type="file" class="form-control">
                        </div>
                    </div>
                    <div class="row my-2">
                        <div class="col-lg-12">
                            <label for="product_description" class="form-label">Product Description</label>
                            <textarea name="" id="" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex align-items-center">
            <p class="form-label mx-2">Add Variants</p>
            <i class="bi bi-plus-square text-success fs-6 me-1" id="add-variation"></i>
            <i class="bi bi-dash-square text-danger fs-6" id="remove-variation"></i>
        </div>

        <div class="row" id="variations-group">
            <div class="col-lg-12 mt-2 variation">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-lg-3">
                            <label for="select" class="form-label">Select</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label for="value" class="form-label">Value</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label for="purchased_price" class="form-label">Purchased Price</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="tickets" class="form-label">Tickets</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="kyat" class="form-label">Kyat</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-4 my-3">
                            <label for="product_img" class="form-label">Product Image</label>
                            <input type="file" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 d-flex justify-content-center">
                <button class="btn btn-danger mx-2">Cancel</button>
                <button class="btn btn-primary mx-2">Save</button>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function() {
   
    $('#add-variation').click(function() {
        // var variation_count = $('.variation').length + 1;
        var variationGroup = $('#variations-group');
        var variation = $('.variation').first().clone();

        // Clear input values in the cloned variation
        variation.find('input').val('');

        // Append the cloned variation to the variations group
        variationGroup.append(variation);

        
    });

    $('#remove-variation').click(function() {
        var variation_count = $('.variation').length;
        if(variation_count > 1){
            var variation = $('.variation').last().remove();
        }else{
            alert('minimum one variant');
        }
        
        console.log(variation_count);
    });
});


</script>
    
@endsection
