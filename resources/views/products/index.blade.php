@extends('layouts.app')

@section('styles')
    <style>
        .btng {
            background-color: red;
            border: none;
            color: white;
            padding: 5px 5px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 15px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 20px;
        }

        .green {
            background-color: #199319;
        }

        .red {
            background-color: red;
        }

        th,
        td {
            white-space: nowrap;
        }

        table img {
            width: 60px;
            height: 60px;
        }



        .dataTables_filter {
            text-align: left !important;
            float: left !important;
            margin: 10px 10px;
        }
    </style>
@endsection
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Product</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3">
                    <form action="{{ route('product.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-lg-4 col-sm-12">
                                <input type="file" class="form-control" name="file">
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <button class="btn btn-primary">Submit</button>
                                <a href="{{ route('product.sample-export') }}" class="btn btn-success">Download Template</a>
                            </div>

                            <div class="col-lg-4 col-sm-12 d-flex justify-content-end">
                                {{-- <a href="{{ route('products.list') }}" class="btn btn-red me-2">Print</a>
                                <a href="{{ route('product.export') }}" class="btn btn-red me-2">Export to Excel</a> --}}
                                <a class="btn btn-blue" href="{{ route('products.create') }}">Add +</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Display the list of products -->

        <table id="table_id">
            <thead>
                {{-- <tr>
                    <th>ID</th>
                    <th>Item Code</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Points</th>
                    <th>Tickets</th>
                    <th>Kyat</th>
                    <th>Received Qty</th>
                    <th>Company Name</th>
                    <th>Country</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Unit</th>
                    <th>Received Date</th>
                    <th>Expired Date</th>
                </tr> --}}
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>SKU</th>
                    <th>Description</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Company Name</th>
                    <th>Country</th>
                    <th>Received Date</th>
                    <th>Expired Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($products as $product)
                    {{-- <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $product->item_code }}</td>
                        <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"></td>
                        <td>{{ $product->product->product_name }}</td>
                        <td>{{ $product->points }}</td>
                        <td>{{ $product->tickets }}</td>
                        <td>{{ $product->kyat }}</td>
                        <td>{{ isset($product->outlet_item->quantity ) ?? $product->outlet_item->quantity}}</td>

                        <td>{{ $product->product->company_name }}</td>
                        <td>{{ $product->product->country }}</td>
                        <td>{{ $product->product->category->category_name }}</td>
                        <td>{{ $product->product->brand->brand_name }}</td>
                        <td>{{ $product->product->unit->name }}</td>
                        <td>{{ $product->product->received_date }}</td>
                        <td>{{ $product->product->expired_date }}</td>
                    </tr> --}}
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->brand->brand_name }}</td>
                        <td>{{ $product->category->category_name }}</td>
                        <td>{{ $product->unit->name }}</td>
                        <td>{{ $product->company_name }}</td>
                        <td>{{ $product->country }}</td>
                        <td>{{ $product->received_date }}</td>
                        <td>{{ $product->expired_date }}</td>
                        <td>
                            <a class="text-decoration-underline px-3" href="{{ route('products.edit', $product->id) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
@endsection
