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


        {!! Form::open([
            'route' => ['products.update', $product->id],
            'method' => 'PUT',
            'enctype' => 'multipart/form-data',
        ]) !!}
        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="product_name" class="form-label">Product Name *</label>
                            {!! Form::text('product_name', $product->product_name, [
                                'placeholder' => 'Product Name',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="category" class="form-label">Category</label>
                            {!! Form::select('category_id', $categories->pluck('category_name', 'id'), $product->category->id, [
                                'placeholder' => 'Choose Category',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4">
                            <label for="brand" class="form-label">Brand</label>
                            {!! Form::select('brand_id', $brands->pluck('brand_name', 'id'), $product->brand->id, [
                                'placeholder' => 'Choose Brand',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="unit" class="form-label">Unit *</label>
                            {!! Form::select('unit_id', $units->pluck('name', 'id'), $product->unit->id, [
                                'placeholder' => 'Choose Unit',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="company_name" class="form-label">Company Name</label>
                            {!! Form::text('company_name', $product->company_name, [
                                'placeholder' => 'Company Name',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="country" class="form-label">Country</label>
                            {!! Form::text('country', $product->country, ['placeholder' => 'Country', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="sku" class="form-label">SKU *</label>
                            {!! Form::text('sku', $product->sku, ['placeholder' => 'SKU', 'class' => 'form-control']) !!}
                        </div>
                        <div class="col-lg-4 my-2">
                            <label for="received_date" class="form-label">Received Date</label>
                            {!! Form::date('received_date', $product->received_date, [
                                'placeholder' => 'Received date',
                                'class' => 'form-control',
                            ]) !!}
                        </div>

                        <div class="col-lg-4 my-2">
                            <label for="expired_date" class="form-label">Expired Date</label>
                            {!! Form::date('expired_date', $product->expired_date, [
                                'placeholder' => 'Expired date',
                                'class' => 'form-control',
                            ]) !!}
                        </div>

                    </div>
                    <div class="row my-2">
                        <div class="col-lg-12">
                            <label for="description" class="form-label">Product Description</label>
                            {!! Form::textarea('description', $product->description, [
                                'cols' => '30',
                                'rows' => '5',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Code</th>
                    <th>Image</th>
                    <th>Select</th>
                    <th>Value</th>
                    <th>Point</th>
                    <th>Ticket</th>
                    <th>Kyat</th>
                    <th>Alert Qty</th>
                    <th>Received Qty</th>
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

                            {!! Form::select('variations[' . $index . '][select]', ['Size' => 'Size'], $variation->select, [
                                'class' => 'form-control',
                                'disabled',
                            ]) !!}
                            {!! Form::hidden('variations[' . $index . '][select]', $variation->select, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][value]', $variation->value, [
                                'class' => 'form-control',
                                'disabled',
                            ]) !!}
                            {!! Form::hidden('variations[' . $index . '][value]', $variation->value, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][points]', $variation->points, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][tickets]', $variation->tickets, [
                                'class' => 'form-control',
                            ]) !!}</td>
                        <td>
                            {!! Form::text('variations[' . $index . '][kyat]', $variation->kyat, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][alert_qty]', $variation->alert_qty, [
                                'class' => 'form-control',
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
                            <a class="text-decoration-underline px-3" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addStockModal{{ $variation->id }}">add stock</a>
                            {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Launch static backdrop modal
                              </button> --}}
                        </td>
                        <td>
                            {!! Form::text('variations[' . $index . '][purchased_price]', $variation->purchased_price, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                       
                    </tr>

                    @php $index++ @endphp
                    
                @endforeach
            </tbody>
        </table>





        <button class="btn btn-red" type="submit">Save</button>


        {!! Form::close() !!}

    </div>
    @foreach ($variations as $variation)
    @include('products.add-stock-modal')
    @endforeach
   

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
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            <img class="imagePreview" src="{{ asset('assets/images/file-upload-icon.png') }}" alt="Old Image">
                            <input class="fileInput" type="file" style="display: none;" name="variations[${variationCount}][image]">
                        </td>
                        <td>
                            {!! Form::select('variations[${variationCount}][select]', ['size' => 'Size'], null, [
                                'placeholder' => 'Choose Select',
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][value]', null, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][points]', null, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][tickets]', null, [
                                'class' => 'form-control',
                            ]) !!}</td>
                        <td>
                            {!! Form::text('variations[${variationCount}][kyat]', null, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][alert_qty]', null, [
                                'class' => 'form-control',
                            ]) !!}
                        </td>
                        <td>
                            {!! Form::text('variations[${variationCount}][received_qty]', 0, [
                                'class' => 'form-control',
                                'disabled',
                            ]) !!}
                            {!! Form::hidden('variations[${variationCount}][received_qty]', 0, [
                                'class' => 'form-control',
                            ]) !!}
                            
                        </td>
                        <td>
                            
                        </td>
                        </td>
                       
                        <td>
                            {!! Form::text('variations[${variationCount}][purchased_price]', null, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
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

        // $('.imagePreview').click(function() {
        //     console.log('ccc');
        //     $(this).siblings('.fileInput').click();
        // });

        
    </script>
@endsection
<script></script>
@endsection
