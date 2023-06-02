@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Create brand</h4>
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
    @csrf
    <!-- Create a new product form -->
<h1>Create Product</h1>
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- General Product Information -->
    <div>
        <label for="item_code">Item Code</label>
        <input type="text" name="item_code" id="item_code">
    </div>

    <div>
        <label for="product_id">Product ID</label>
        <input type="text" name="product_id" id="product_id">
    </div>

    <div>
        <label for="company_name">Company Name</label>
        <input type="text" name="company_name" id="company_name">
    </div>

    <div>
        <label for="product_name">Product Name</label>
        <input type="text" name="product_name" id="product_name">
    </div>

    <div>
        <label for="country">Country</label>
        <input type="text" name="country" id="country">
    </div>

    <div>
        <label for="unit">Unit</label>
        <input type="text" name="unit" id="unit">
    </div>

    <div>
        <label for="brand">Brand</label>
        <input type="text" name="brand" id="brand">
    </div>


    <!-- Product Description -->
    <div>
        <label for="product_description">Product Description</label>
        <textarea name="product_description" id="product_description"></textarea>
    </div>

    <!-- Variations -->
    <div id="variations-group">
        <h3>Variations</h3>
        <div class="variation">
            <div>
                <label for="variation_select[]">Select Value</label>
                <input type="text" name="variation_select[]" class="variation-select">
            </div>
            <div>
                <label for="purchase_price[]">Purchase Price</label>
                <input type="number" name="purchase_price[]" class="purchase-price">
            </div>
            <div>
                <label for="points[]">Points</label>
                <input type="number" name="points[]" class="points">
            </div>
            <div>
                <label for="tickets[]">Tickets</label>
                <input type="number" name="tickets[]" class="tickets">
            </div>
            <div>
                <label for="kyat[]">Kyat</label>
                <input type="number" name="kyat[]" class="kyat">
            </div>
            
        </div>
    </div>

    <button type="button" id="add-variation">Add Variation</button>

    <button type="submit">Save</button>
</form>
</div>
<script>
    $(document).ready(function() {
    $('#add-variation').click(function() {
        var variationGroup = $('#variations-group');
        var variation = $('.variation').first().clone();

        // Clear input values in the cloned variation
        variation.find('input').val('');

        // Append the cloned variation to the variations group
        variationGroup.append(variation);
    });
});

</script>
    
@endsection
