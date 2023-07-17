@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Add Product</h4>
            <div>
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

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {!! Form::open(['route' => 'products.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="product_name" class="form-label">Product Name *</label>
                            {!! Form::text('product_name', null, ['placeholder' => 'Product Name', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="category" class="form-label">Category</label>
                            {!! Form::select('category_id', $categories->pluck('category_name', 'id'), null, [
                                'placeholder' => 'Choose Category',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="brand" class="form-label">Brand</label>
                            {!! Form::select('brand_id', $brands->pluck('brand_name', 'id'), null, [
                                'placeholder' => 'Choose Brand',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="unit" class="form-label">Unit *</label>
                            {!! Form::select('unit_id', $units->pluck('name', 'id'), null, [
                                'placeholder' => 'Choose Unit',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="company_name" class="form-label">Company Name</label>
                            {!! Form::text('company_name', null, ['placeholder' => 'Company Name', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="country" class="form-label">Country</label>
                            {!! Form::text('country', null, ['placeholder' => 'Country', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="sku" class="form-label">SKU *</label>
                            {!! Form::text('sku', null, ['placeholder' => 'SKU', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="received_date" class="form-label">Received Date</label>
                            {!! Form::date('received_date', null, ['placeholder' => 'Received date', 'class' => 'form-control']) !!}
                        </div>

                        <div class="col-lg-4 my-2">
                            <label for="expired_date" class="form-label">Expired Date</label>
                            {!! Form::date('expired_date', null, ['placeholder' => 'Expired date', 'class' => 'form-control']) !!}
                        </div>

                    </div>
                    <div class="row my-2">
                        <div class="col-lg-12">
                            <label for="description" class="form-label">Product Description</label>
                            {!! Form::textarea('description', null, ['cols' => '30', 'rows' => '5', 'class' => 'form-control']) !!}
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
                            {!! Form::select('variations[0][select]', ['size' => 'Size'], null, [
                                'placeholder' => 'Choose Select',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3">
                            <label for="value" class="form-label">Value</label>
                            {!! Form::text('variations[0][value]', null, ['placeholder' => 'Value', 'class' => 'form-control']) !!}

                        </div>
                        <div class="col-lg-3">
                            <label for="received_qty" class="form-label">Received Qty</label>
                            {!! Form::text('variations[0][received_qty]', null, [
                                'placeholder' => 'Received Quantity',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3">
                            <label for="alert_quantity" class="form-label">Alert Quantity</label>
                            {!! Form::text('variations[0][alert_qty]', null, [
                                'placeholder' => 'Alert Quantity',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="item_code" class="form-label">Item Code</label>
                            {!! Form::text('variations[0][item_code]', null, [
                                'placeholder' => 'Item Code',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="points" class="form-label">Points</label>
                            {!! Form::text('variations[0][points]', null, [
                                'placeholder' => 'Points',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="tickets" class="form-label">Tickets</label>
                            {!! Form::text('variations[0][tickets]', null, [
                                'placeholder' => 'Tickets',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="kyat" class="form-label">Kyat</label>
                            {!! Form::text('variations[0][kyat]', null, [
                                'placeholder' => 'Kyat',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="purchased_price" class="form-label">Purchased Price</label>
                            {!! Form::text('variations[0][purchased_price]', null, [
                                'placeholder' => 'Purchased Price',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="product_img" class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="variations[0][image]">
                        </div>
                        <div class="col-lg-4">
                            <label for="barcode" class="form-label">Barcode</label>
                            {!! Form::text('variations[0][barcode]', null, [
                                'placeholder' => 'Barcode',
                                'class' => 'form-control',
                            ]) !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex justify-content-center">
                <a href="{{ URL::current() }}" class="btn btn-red mx-2">Cancel</a>
                <button class="btn btn-blue mx-2" type="submit">Save</button>
            </div>
        </div>


        {!! Form::close() !!}

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
                            {!! Form::select('variations[${variationCount}][select]', ['size' => 'Size'], null, [
                                'placeholder' => 'Choose Select',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3">
                            <label for="value" class="form-label">Value</label>
                            {!! Form::text('variations[${variationCount}][value]', null, [
                                'placeholder' => 'Value',
                                'class' => 'form-control',
                            ]) !!}

                        </div>
                        <div class="col-lg-3">
                            <label for="received_qty" class="form-label">Received Qty</label>
                            {!! Form::text('variations[${variationCount}][received_qty]', null, [
                                'placeholder' => 'Received Quantity',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3">
                            <label for="alert_quantity" class="form-label">Alert Quantity</label>
                            {!! Form::text('variations[${variationCount}][alert_qty]', null, [
                                'placeholder' => 'Alert Quantity',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="item_code" class="form-label">Item Code</label>
                            {!! Form::text('variations[${variationCount}][item_code]', null, [
                                'placeholder' => 'Item Code',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="points" class="form-label">Points</label>
                            {!! Form::text('variations[${variationCount}][points]', null, [
                                'placeholder' => 'Points',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="tickets" class="form-label">Tickets</label>
                            {!! Form::text('variations[${variationCount}][tickets]', null, [
                                'placeholder' => 'Tickets',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-3 my-3">
                            <label for="kyat" class="form-label">Kyat</label>
                            {!! Form::text('variations[${variationCount}][kyat]', null, [
                                'placeholder' => 'Kyat',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="purchased_price" class="form-label">Purchased Price</label>
                            {!! Form::text('variations[${variationCount}][purchased_price]', null, [
                                'placeholder' => 'Purchased Price',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="product_img" class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="variations[${variationCount}][image]" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="barcode" class="form-label">Barcode</label>
                            {!! Form::text('variations[${variationCount}][barcode]', null, [
                                'placeholder' => 'Barcode',
                                'class' => 'form-control',
                            ]) !!}
                        </div>

                    </div>
                    </div>
                </div>`;

                $(".variation:last").after(variationTemplate);

            });




        });
    </script>
@endsection
