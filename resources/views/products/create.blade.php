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
                @include('breadcrumbs')
            </div>
        </div>
      
        {!! Form::open([
            'route' => 'products.store',
            'method' => 'POST',
            'enctype' => 'multipart/form-data',
            'id' => 'productForm',
        ]) !!}
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3">
                    <div class="row g-2">
                        <div class="col-lg-4">
                            {{ Form::label('product_name', 'Product Name', ['class' => 'form-label', 'id' => 'product_name_label']) }}
                            {{ Form::text('product_name', null, ['class' => 'form-control', 'id' => 'product_name', 'placeholder' => 'Product Name']) }}
                            <span class="text-danger error" id="product_name_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('category_id', 'Category', ['class' => 'form-label', 'id' => 'category_id_label']) }}
                            {{ Form::select('category_id', ['' => 'Choose Category'] + $categories->pluck('category_name', 'id')->toArray(), null, ['class' => 'form-control']) }}
                            <span class="text-danger error" id="category_id_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('brand_id', 'Brand', ['class' => 'form-label', 'id' => 'brand_id_label']) }}
                            {{ Form::select('brand_id', ['' => 'Choose Brand'] + $brands->pluck('brand_name', 'id')->toArray(), null, ['class' => 'form-control']) }}
                            <span class="text-danger error" id="brand_id_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('unit_id', 'Unit', ['class' => 'form-label', 'id' => 'unit_id_label']) }}
                            {{ Form::select('unit_id', ['' => 'Choose Unit'] + $units->pluck('name', 'id')->toArray(), null, ['class' => 'form-control']) }}
                            <span class="text-danger error" id="unit_id_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('company_name', 'Company Name', ['class' => 'form-label', 'id' => 'company_name_label']) }}
                            {{ Form::text('company_name', null, ['class' => 'form-control', 'id' => 'company_name', 'placeholder' => 'Company Name']) }}
                            <span class="text-danger error" id="company_name_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('country', 'Country', ['class' => 'form-label', 'id' => 'country_label']) }}
                            {{ Form::text('country', null, ['class' => 'form-control', 'id' => 'country', 'placeholder' => 'Country']) }}
                            <span class="text-danger error" id="country_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('sku', 'SKU', ['class' => 'form-label', 'id' => 'sku_label']) }}
                            {{ Form::text('sku', null, ['class' => 'form-control', 'id' => 'sku', 'placeholder' => 'SKU']) }}
                            <span class="text-danger error" id="sku_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('received_date', 'Received Date', ['class' => 'form-label', 'id' => 'received_date_label']) }}
                            {{ Form::date('received_date', null, ['class' => 'form-control', 'id' => 'received_date']) }}
                            <span class="text-danger error" id="received_date_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('expired_date', 'Expired Date', ['class' => 'form-label', 'id' => 'expired_date_label']) }}
                            {{ Form::date('expired_date', null, ['class' => 'form-control', 'id' => 'expired_date']) }}
                            <span class="text-danger error" id="expired_date_error"></span>
                        </div>
                        <div class="col-lg-12">
                            {{ Form::label('description', 'Product Description', ['class' => 'form-label', 'id' => 'description']) }}
                            {!! Form::textarea('description', null, ['cols' => '30', 'rows' => '5', 'class' => 'form-control']) !!}
                            <span class="text-danger error" id="description_error"></span>
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
                    <div class="row g-3">
                        <div class="col-lg-3">
                            {{ Form::label('select', 'Select', ['class' => 'form-label', 'id' => 'variations_0_select_label']) }}
                            {{ Form::select('variations[0][select]', ['size' => 'Size'], null, ['class' => 'form-control', 'placeholder' => 'Choose Select', 'id'=> 'variations_0_select']) }}
                            <span class="text-danger error" id="variations_0_select_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('value', 'Value', ['class' => 'form-label', 'id' => 'variations_0_value_label']) }}
                            {{ Form::text('variations[0][value]', null, ['class' => 'form-control', 'id' => 'variations_0_value', 'placeholder' => 'Value']) }}
                            <span class="text-danger error" id="variations_0_value_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('received_qty', 'Received Qty', ['class' => 'form-label' ,'id' => 'variations_0_received_qty_label' ]) }}
                            {{ Form::text('variations[0][received_qty]', null, ['class' => 'form-control', 'id' => 'variations_0_received_qty', 'placeholder' => 'Received Quantity']) }}
                            <span class="text-danger error" id="variations_0_received_qty_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('alert_qty', 'Alert Qty', ['class' => 'form-label' , 'id' => 'variations_0_alert_qty_label' ]) }}
                            {{ Form::text('variations[0][alert_qty]', null, ['class' => 'form-control', 'id' => 'variations_0_alert_qty', 'placeholder' => 'Alert Quantity']) }}
                            <span class="text-danger error" id="variations_0_alert_qty_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('item_code', 'Item Code', ['class' => 'form-label', 'id' => 'variations_0_item_code_label']) }}
                            {{ Form::text('variations[0][item_code]', null, ['class' => 'form-control', 'id' => 'variations_0_item_code', 'placeholder' => 'Item Code']) }}
                            <span class="text-danger error" id="variations_0_item_code_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('points', 'Points', ['class' => 'form-label', 'id' => 'variations_0_points_label']) }}
                            {{ Form::text('variations[0][points]', null, ['class' => 'form-control', 'id' => 'variations_0_points', 'placeholder' => 'Points']) }}
                            <span class="text-danger error" id="variations_0_points_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('tickets', 'Tickets', ['class' => 'form-label', 'id' => 'variations_0_tickets_label']) }}
                            {{ Form::text('variations[0][tickets]', null, ['class' => 'form-control', 'id' => 'variations_0_tickets', 'placeholder' => 'Tickets']) }}
                            <span class="text-danger error" id="variations_0_tickets_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('kyat', 'Kyat', ['class' => 'form-label', 'id' => 'variations_0_kyat_label']) }}
                            {{ Form::text('variations[0][kyat]', null, ['class' => 'form-control', 'id' => 'variations_0_kyat', 'placeholder' => 'Kyat']) }}
                            <span class="text-danger error" id="variations_0_kyat_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('purchased_price', 'Purchased Price', ['class' => 'form-label' , 'id' => 'variations_0_purchased_price_label']) }}
                            {{ Form::text('variations[0][purchased_price]', null, ['class' => 'form-control', 'id' => 'variations_0_purchased_price', 'placeholder' => 'Purchased Price']) }}
                            <span class="text-danger error" id="variations_0_purchased_price_error"></span>
                        </div>
                        <div class="col-lg-4">
                            <label for="image" class="form-label" id="variations_0_image_label">Product Image</label>
                            <input type="file" class="form-control" name="variations[0][image]" id="variations_0_image">
                            <span class="text-danger error" id="variations_0_image_error"></span>
                        </div>
                        <div class="col-lg-4">
                            <label for="barcode" class="form-label">Barcode</label>
                            {{ Form::label('barcode', 'Barcode', ['class' => 'form-label' , 'id' => 'variations_0_barcode_label']) }}
                            {!! Form::text('variations[0][barcode]', null, [
                                'placeholder' => 'Barcode',
                                'class' => 'form-control',
                                'id' => 'variations_0_barcode',
                            ]) !!}
                            <span class="text-danger error" id="variations_0_barcode_error"></span>
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
                       
                                <div class="row g-3">
                       
                        <div class="col-lg-3">
                            {{ Form::label('select', 'Select', ['class' => 'form-label', 'id' => 'variations_${variationCount}_select_label']) }}
                            {{ Form::select('variations[${variationCount}][select]', ['size' => 'Size'], null, ['class' => 'form-control', 'placeholder' => 'Choose Select', 'id'=> 'variations_${variationCount}_select']) }}
                            <span class="text-danger error" id="variations_${variationCount}_select_error"></span>
                        </div>


                       <div class="col-lg-3">
                            {{ Form::label('value', 'Value', ['class' => 'form-label', 'id' => 'variations_${variationCount}_value_label']) }}
                            {{ Form::text('variations[${variationCount}][value]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_value', 'placeholder' => 'Value']) }}
                            <span class="text-danger error" id="variations_${variationCount}_value_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('received_qty', 'Received Qty', ['class' => 'form-label' ,'id' => 'variations_${variationCount}_received_qty_label' ]) }}
                            {{ Form::text('variations[${variationCount}][received_qty]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_received_qty', 'placeholder' => 'Received Quantity']) }}
                            <span class="text-danger error" id="variations_${variationCount}_received_qty_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('alert_qty', 'Alert Qty', ['class' => 'form-label' , 'id' => 'variations_${variationCount}_alert_qty_label' ]) }}
                            {{ Form::text('variations[${variationCount}][alert_qty]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_alert_qty', 'placeholder' => 'Alert Quantity']) }}
                            <span class="text-danger error" id="variations_${variationCount}_alert_qty_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('item_code', 'Item Code', ['class' => 'form-label', 'id' => 'variations_${variationCount}_item_code_label']) }}
                            {{ Form::text('variations[${variationCount}][item_code]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_item_code', 'placeholder' => 'Item Code']) }}
                            <span class="text-danger error" id="variations_${variationCount}_item_code_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('points', 'Points', ['class' => 'form-label', 'id' => 'variations_${variationCount}_points_label']) }}
                            {{ Form::text('variations[${variationCount}][points]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_points', 'placeholder' => 'Points']) }}
                            <span class="text-danger error" id="variations_${variationCount}_points_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('tickets', 'Tickets', ['class' => 'form-label', 'id' => 'variations_${variationCount}_tickets_label']) }}
                            {{ Form::text('variations[${variationCount}][tickets]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_tickets', 'placeholder' => 'Tickets']) }}
                            <span class="text-danger error" id="variations_${variationCount}_tickets_error"></span>
                        </div>
                        <div class="col-lg-3">
                            {{ Form::label('kyat', 'Kyat', ['class' => 'form-label', 'id' => 'variations_${variationCount}_kyat_label']) }}
                            {{ Form::text('variations[${variationCount}][kyat]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_kyat', 'placeholder' => 'Kyat']) }}
                            <span class="text-danger error" id="variations_${variationCount}_kyat_error"></span>
                        </div>
                        <div class="col-lg-4">
                            {{ Form::label('purchased_price', 'Purchased Price', ['class' => 'form-label' , 'id' => 'variations_${variationCount}_purchased_price_label']) }}
                            {{ Form::text('variations[${variationCount}][purchased_price]', null, ['class' => 'form-control', 'id' => 'variations_${variationCount}_purchased_price', 'placeholder' => 'Purchased Price']) }}
                            <span class="text-danger error" id="variations_${variationCount}_purchased_price_error"></span>
                        </div>
                        <div class="col-lg-4">
                            <label for="image" class="form-label" id="variations_${variationCount}_image_label">Product Image</label>
                            <input type="file" class="form-control" name="variations[${variationCount}][image]" id="variations_${variationCount}_image">
                            <span class="text-danger error" id="variations_${variationCount}_image_error"></span>
                        </div>
                        <div class="col-lg-4">
                            <label for="barcode" class="form-label">Barcode</label>
                            {{ Form::label('barcode', 'Barcode', ['class' => 'form-label' , 'id' => 'variations_${variationCount}_barcode_label']) }}
                            {!! Form::text('variations[${variationCount}][barcode]', null, [
                                'placeholder' => 'Barcode',
                                'class' => 'form-control',
                                'id' => 'variations_${variationCount}_barcode',
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_barcode_error"></span>
                        </div>

                    </div>

                   
                    </div>
                </div>`;

                $(".variation:last").after(variationTemplate);

            });




            $("#productForm").on('submit', function(event) {
                event.preventDefault();
                console.log('submit');
                $('input.form-control').removeClass('is-invalid');
                $('select').removeClass('is-invalid');
                $('.form-label').removeClass('text-danger');
                $('.error').empty();

                $.ajax({
                    type: "POST",
                    url: "{{ route('products.store') }}",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                      
                        console.log(response);
                        var successMessage = $(
                            '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            response.message +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );

                        $('.breadcrumbBox').after(successMessage);
                        window.scrollTo(0, 0);
                        $('#productForm')[0].reset();

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    window.scrollTo(0, 0);
                                    var errorKey = key.replace(/\./g, '_');
                                    $('#' + errorKey).addClass('is-invalid');
                                    $('#' + errorKey + '_label').addClass('text-danger');
                                    $('#' + errorKey + '_error').text(errors[key][0]);
                                }
                            }
                        }
                    }
                });
            });

        });
    </script>
@endsection
