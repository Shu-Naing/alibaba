@extends('layouts.app')
@section('styles')
    <style>
        th,
        td {
            white-space: nowrap;
        }



        table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        table th,
        td {
            text-align: center;
            vertical-align: middle;
        }

        .modal-bg{
            background-color: var(--primary-color);
            
        }

        .modal-bg h1{
            color: white;
        }

    </style>
@endsection
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Edit Product</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
       
        {!! Form::model($product, [
            'method' => 'PUT',
            'route' => ['products.update', $product->id],
            'enctype' => 'multipart/form-data',
            'id' => 'productEditForm',
        ]) !!}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card p-3">
                        {{-- <div class="row g-2">
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
                                {{ Form::label('brand_id', 'Brand', ['class' => 'form-label'. ($errors->has('brand_id') ? ' text-danger' : '')]) }}
                                {{ Form::select('brand_id', ['' => 'Choose Brand'] + $brands->pluck('brand_name', 'id')->toArray(), null, ['class' => 'form-control' . ($errors->has('brand_id') ? ' is-invalid' : '')]) }}
                                @error('brand_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                {{ Form::label('unit_id', 'Unit', ['class' => 'form-label'. ($errors->has('unit_id') ? ' text-danger' : '')]) }}
                                {{ Form::select('unit_id', ['' => 'Choose Unit'] + $units->pluck('name', 'id')->toArray(), null, ['class' => 'form-control' . ($errors->has('unit_id') ? ' is-invalid' : '')]) }}
                                @error('unit_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                {{ Form::label('company_name', 'Company Name', ['class' => 'form-label'. ($errors->has('company_name') ? ' text-danger' : '')]) }}
                                {{ Form::text('company_name', null, ['class' => 'form-control' . ($errors->has('company_name') ? ' is-invalid' : ''), 'id' => 'company_name', 'placeholder' => 'Company Name']) }}
                                @error('company_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-4">
                                {{ Form::label('country', 'Country', ['class' => 'form-label'. ($errors->has('country') ? ' text-danger' : '')]) }}
                                {{ Form::text('country', null, ['class' => 'form-control' . ($errors->has('country') ? ' is-invalid' : ''), 'id' => 'country', 'placeholder' => 'Country']) }}
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-4">
                                {{ Form::label('sku', 'SKU', ['class' => 'form-label'. ($errors->has('sku') ? ' text-danger' : '')]) }}
                                {{ Form::text('sku', null, ['class' => 'form-control' . ($errors->has('sku') ? ' is-invalid' : ''), 'id' => 'sku', 'placeholder' => 'SKU']) }}
                                @error('sku')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        
                            <div class="col-lg-4">
                                {{ Form::label('received_date', 'Received Date', ['class' => 'form-label'. ($errors->has('received_date') ? ' text-danger' : '')]) }}
                                {{ Form::date('received_date', null, ['class' => 'form-control' . ($errors->has('received_date') ? ' is-invalid' : ''), 'id' => 'received_date']) }}
                                @error('received_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                    
                            <div class="col-lg-4">
                                {{ Form::label('expired_date', 'Expired Date', ['class' => 'form-label'. ($errors->has('expired_date') ? ' text-danger' : '')]) }}
                                {{ Form::date('expired_date', null, ['class' => 'form-control' . ($errors->has('expired_date') ? ' is-invalid' : ''), 'id' => 'expired_date']) }}
                                @error('expired_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                {{ Form::label('description', 'Product Description', ['class' => 'form-label', 'id' => 'description']) }}
                                {!! Form::textarea('description', null, ['cols' => '30', 'rows' => '5', 'class' => 'form-control']) !!}
                                <span class="text-danger error" id="description_error"></span>
                            </div>
                        </div> --}}
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
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Code</th>
                    <th>Image</th>
                    <th>Size Variant</th>
                    <th>GRN No</th>
                    <th>Point</th>
                    <th>Ticket</th>
                    <th>Kyat</th>
                    <th>Alert Qty</th>
                    <th>Received Qty</th>
                    <th>Barcode</th>
                    <th>Add Stock</th>
                    <th>Purchased Price</th>
                    <th>
                        <i class="fs-6 bi bi-plus-square-fill" id="add-variation"></i>
                    </th>
                </tr>
            </thead>
           <tbody>
                @php
                    $no = 1;
                    $index = 0;
                @endphp
                @foreach ($variations as $variation)
                    <tr class="variation">

                        <td>{{ $no++ }}</td>
                        <td>
                            {!! Form::text('variations[' . $index . '][item_code]', $variation->item_code, [
                                'class' => 'form-control',
                                'disabled',
                                'style' => 'width: 100px',
                            ]) !!}
                            {!! Form::hidden('variations[' . $index . '][item_code]', $variation->item_code, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            <img class="imagePreview" src="{{ asset('storage/' . $variation->image) }}" alt="Old Image">
                            <input class="fileInput" type="file" style="display: none;"
                                name="variations[{{ $index }}][image]">
                        </td>
                        <td>
                            {{ Form::select('variations[' . $index . '][size_variant_value]', ['' => 'Choose Size Variant'] + $sizeVariants->pluck('value', 'id')->toArray(),$variation->size_variant_value , ['class' => 'form-control', 'id'=> 'variations_0_size_variant_value', 'style' => 'width: 200px',]) }}
                        </td>
                        <td>
                            {{ Form::text('variations[' . $index . '][grn_no]', $variation->grn_no, ['class' => 'form-control', 'style' => 'width: 60px',]) }}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][points]', $variation->points, [
                                'class' => 'form-control',
                                'required',
                                'style' => 'width: 60px',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][tickets]', $variation->tickets, [
                                'class' => 'form-control',
                                'required',
                                'style' => 'width: 60px',
                            ]) !!}</td>
                        <td>
                            {!! Form::text('variations[' . $index . '][kyat]', $variation->kyat, [
                                'class' => 'form-control',
                                'required',
                                'style' => 'width: 100px',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][alert_qty]', $variation->alert_qty, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][received_qty]', outlet_stock($variation->id), [
                                'class' => 'form-control',
                                'disabled',
                            ]) !!}
                            {!! Form::hidden('variations[' . $index . '][received_qty]', outlet_stock($variation->id), [
                                'class' => 'form-control',
                            ]) !!}
                            
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][barcode]', $variation->barcode, [
                                'class' => 'form-control',
                                'required',
                                'style' => 'width: 100px',
                            ]) !!}
                        </td>
                        <td>
                            <a class="text-decoration-underline px-3" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $variation->id }}">add stock</a>
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][purchased_price]', $variation->purchased_price, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </td>
                    
                    </tr>

                    @php $index++ @endphp
                    
                @endforeach
            </tbody>
                
            </table>
        </div>
        <button class="btn btn-red" type="submit">Save</button>
        {!! Form::close() !!}
    </div>
    @foreach ($variations as $variation)
    @include('products.add-stock-modal')
    @endforeach
   
@endsection

@section('scripts')
    <script>
        function removeVariation(variation_no) {
            // console.log(variation_no);
            $('#variation_' + variation_no).remove();
        }

        function addStockBtn(variation_id){
               
            var addStockForm = $('#addStockForm'+variation_id);
            // var url = 'products-add-stock/'+variation_id;
            $.ajax({
                url: addStockForm.attr('action'),
                type: addStockForm.attr('method'),
                _token : "{{ csrf_token() }}",
                data: addStockForm.serialize(),
                success : function(response) {

                    console.log(response);
                    location.reload();

                },
                error: function(e){
                    console.log("asd");
                    console.log(e.responseText);
                }
            });
        }

        $(document).ready(function() {
            function bindImagePreviewClickEvent() {
                $('.imagePreview').off('click').on('click', function() {
                    $(this).siblings('.fileInput').click();
                });
            }

            function bindFileInputChangeEvent() {
                $(document).off('change', '.fileInput').on('change', '.fileInput', function(event) {
                    var file = event.target.files[0];
                    var reader = new FileReader();
                    var imagePreview = $(this).siblings('.imagePreview')[0];
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            }

            bindImagePreviewClickEvent();
            bindFileInputChangeEvent()

            $("#add-variation").off('click').on('click', function() {
                var variationCount = $('.variation').length;
                var no = variationCount + 1;
                var variationTemplate = ` <tr class="variation" id="variation_${no}">
                        
                        <td>${no}</td>
                        <td>
                            {!! Form::text('variations[${variationCount}][item_code]', null, [
                                'class' => 'form-control', 'id' => 'variations_${variationCount}_item_code',
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_item_code_error"></span>
                        </td>
                        <td>
                            <img class="imagePreview" src="{{ asset('assets/images/dummy-post-horisontal.jpg') }}" alt="Old Image">
                            <input class="fileInput" type="file" style="display: none;" name="variations[${variationCount}][image]">
                        </td>
                        <td>
                            {{ Form::select('variations[${variationCount}][size_variant_value]', ['' => 'Choose Size Variant'] + $sizeVariants->pluck('value', 'id')->toArray(),null , ['class' => 'form-control', 'id' => 'variations_${variationCount}_size_variant_value']) }}
                            <span class="text-danger error" id="variations_${variationCount}_size_variant_value_error"></span>
                        </td>
                        <td>
                            {{ Form::text('variations[${variationCount}][grn_no]', null, ['class' => 'form-control' , 'id' => 'variations_${variationCount}_grn_no']) }}
                            <span class="text-danger error" id="variations_${variationCount}_grn_no_error"></span>
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][points]', null, [
                                'class' => 'form-control',
                                'id' => 'variations_${variationCount}_points'
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_points_error"></span>
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][tickets]', null, [
                                'class' => 'form-control',
                                'id' => 'variations_${variationCount}_tickets'
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_tickets_error"></span>
                        </td>
                            
                        <td>
                            {!! Form::text('variations[${variationCount}][kyat]', null, [
                                'class' => 'form-control',
                                'id' => 'variations_${variationCount}_kyat'
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_kyat_error"></span>
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][alert_qty]', null, [
                                'class' => 'form-control',
                                'id' => 'variations_${variationCount}_alert_qty'
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_alert_qty_error"></span>
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][received_qty]', null, [
                                'class' => 'form-control',
                                'id' => 'variations_${variationCount}_received_qty'
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_received_qty_error"></span>
                            
                        </td>
                        <td>
                                {!! Form::text('variations[${variationCount}][barcode]', null, [
                                    'class' => 'form-control',
                                    'required',
                                ]) !!}
                            </td>
                        <td>
                            
                        </td>
                        </td>
                       
                        <td>
                            {!! Form::text('variations[${variationCount}][purchased_price]', null, [
                                'class' => 'form-control',
                                'id' => 'variations_${variationCount}_purchased_price'
                            ]) !!}
                            <span class="text-danger error" id="variations_${variationCount}_purchased_price_error"></span>
                        </td>
                        <td>
                            <i class="fs-6 bi bi-dash-square-fill" onclick="removeVariation(${no})"></i>
                        </td>
                    </tr>`;

                $(".variation:last").after(variationTemplate);
                bindImagePreviewClickEvent();
                bindFileInputChangeEvent();

            });
        });      

        $(document).ready(function() {
            $("#productEditForm").on('submit', function(event) {
                event.preventDefault();
                var productEditForm = $(this);
                var formData = new FormData(this);

                $('input.form-control').removeClass('is-invalid');
                $('select').removeClass('is-invalid');
                $('.form-label').removeClass('text-danger');
                $('.error').empty();

                $.ajax({
                    type: productEditForm.attr('method'),
                    url: productEditForm.attr('action'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // console.log(response.message);

                        // Show success message on the page
                        var successMessage = $(
                                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                response.message +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                                );

                                $('.breadcrumbBox').after(successMessage);
                            window.scrollTo(0, 0);
                            $('#productEditForm')[0].reset();

                        // You can also perform other actions after successful update, if needed.

                    },
                    error: function(xhr, status, error) {
                        // console.error(xhr.responseText);

                        // Handle validation errors
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

