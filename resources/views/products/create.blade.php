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
        
        <form action="{{ route('products.store') }}" method="POST" enctype='multipart/form-data'>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="product_name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" name="product_name">
                            </div>
                            <div class="col-lg-4">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" name="category_id">
                                    <option selected>Choose Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="brand" class="form-label">Brand</label>
                                <select class="form-select" name="brand_id">
                                    <option selected>Choose Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 my-2">
                                <label for="unit" class="form-label">Unit *</label>
                               <select class="form-select" name="unit_id">
                                    <option selected>Choose Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>       
                            </div>
                            <div class="col-lg-4 my-2">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name">
                            </div>
                            <div class="col-lg-4 my-2">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" name="country">
                            </div>
                            <div class="col-lg-4 my-2">
                                <label for="sku" class="form-label">SKU *</label>
                                <input type="text" class="form-control" name="sku">
                            </div>
                            <div class="col-lg-4 my-2">
                                <label for="received_date" class="form-label">Received Date</label>
                                <input type="date" class="form-control" name="received_date">
                            </div>
                            {{-- <div class="col-lg-2 my-2">
                                <label for="alert_quantity" class="form-label">Alert Quantity</label>
                                <input type="text" class="form-control" name="quantity">
                            </div>
                            <div class="col-lg-2 my-2 d-flex align-items-center">
                                <label class="form-check-label me-2" for="manage_stock">
                                    Manage Stock
                                    </label>
                                <input class="form-check-input" type="checkbox" value="" id="manage_stock" name="manage_stock"> 
                            </div> --}}
                            {{-- <div class="col-lg-4 my-2">
                                <label for="received_qty" class="form-label">Received Qty</label>
                                <input type="text" class="form-control" name="received_qty">
                            </div> --}}
                            <div class="col-lg-4 my-2">
                                <label for="expired_date" class="form-label">Expired Date</label>
                                <input type="date" class="form-control" name="expired_date">
                            </div>
                            {{-- <div class="col-lg-4 my-2">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="image">
                            </div> --}}
                        </div>
                        <div class="row my-2">
                            <div class="col-lg-12">
                                <label for="description" class="form-label">Product Description</label>
                                <textarea  id="description" cols="30" rows="5" class="form-control" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  


            <div class="d-flex align-items-center">
                <p class="form-label mx-2">Add Variants</p>
                <i class="bi bi-plus-square-fill fs-6 me-1" id="add-variation" style="color:var(--primary-color);"></i>
            </div>

            <div class="row" id="variations-group">
                <div class="col-lg-12 mt-2 variation" id="variation_0">
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="select" class="form-label">Select</label>
                                {{-- <input type="text" class="form-control" name="variations[0][select]"> --}}
                                <select class="form-select" name="variations[0][select]">
                                    <option selected>Choose Select</option>
                                    <option value="size">Size</option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="value" class="form-label">Value</label>
                                <input type="text" class="form-control" name="variations[0][value]">
                            </div>
                            <div class="col-lg-3">
                                <label for="received_qty" class="form-label">Received Qty</label>
                                <input type="text" class="form-control" name="variations[0][received_qty]">
                            </div>
                            <div class="col-lg-3">
                                <label for="alert_quantity" class="form-label">Alert Quantity</label>
                                <input type="text" class="form-control" name="variations[0][alert_qty]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="item_code" class="form-label">Item Code</label>
                                <input type="text" class="form-control" name="variations[0][item_code]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="points" class="form-label">Points</label>
                                <input type="text" class="form-control" name="variations[0][points]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="tickets" class="form-label">Tickets</label>
                                <input type="text" class="form-control" name="variations[0][tickets]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="kyat" class="form-label">Kyat</label>
                                <input type="text" class="form-control" name="variations[0][kyat]">
                            </div>
                            <div class="col-lg-3">
                                <label for="purchased_price" class="form-label">Purchased Price</label>
                                <input type="text" class="form-control" name="variations[0][purchased_price]">
                            </div>
                            <div class="col-lg-4">
                                <label for="product_img" class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="variations[0][image]">
                            </div>
                            {{-- <div class="col-lg-4">
                                <label for="product_img" class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="variations[0][image][]" multiple>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 d-flex justify-content-center">
                    <a href="{{ route('products.create') }}" class="btn btn-red mx-2">Cancel</a>
                    <button class="btn btn-blue mx-2" type="submit">Save</button>
                </div>
            </div>  
        </form>

    </div>
<script>

    function removeVariation(variation_no) {
        $('#variation_' + variation_no).remove();
    }


    $(document).ready(function() {

    $("#add-variation").off('click').on('click', function() {
    var variationCount = $('.variation').length;
    var variationTemplate = `<div class="col-lg-12 mt-2 variation" id="variation_${variationCount}">
                    <div class="card p-3">
                        
                                <i class="bi bi-dash-square-fill fs-6 me-1 text-end" onclick="removeVariation(${variationCount})" style="color:blue;"></i>
                       
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="select" class="form-label">Select</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][select]">
                            </div>
                            <div class="col-lg-3">
                                <label for="value" class="form-label">Value</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][value]">
                            </div>
                            <div class="col-lg-3">
                                <label for="received_qty" class="form-label">Received Qty</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][received_qty]">
                            </div>
                            <div class="col-lg-3">
                                <label for="alert_quantity" class="form-label">Alert Quantity</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][alert_qty]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="item_code" class="form-label">Item Code</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][item_code]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="points" class="form-label">Points</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][points]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="tickets" class="form-label">Tickets</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][tickets]">
                            </div>
                            <div class="col-lg-3 my-3">
                                <label for="kyat" class="form-label">Kyat</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][kyat]">
                            </div>
                            <div class="col-lg-3">
                                <label for="purchased_price" class="form-label">Purchased Price</label>
                                <input type="text" class="form-control" name="variations[${variationCount}][purchased_price]">
                            </div>
                            <div class="col-lg-4">
                                <label for="product_img" class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="variations[${variationCount}][image]">
                            </div>
                        </div>
                    </div>
                </div>`;

                $(".variation:last").after(variationTemplate);

});

   

   
});




</script>
    
@endsection
