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

        <div class="d-flex justify-content-end">
            <a class="btn btn-blue" href="{{ route('products.create') }}">Add +</a>
        </div>
        <!-- Display the list of products -->



        <table id="table_id">
            <thead>
                
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
<<<<<<< HEAD
                    <th>SKU</th>
=======
                    <th>Product Code</th>
                    {{-- <th>Description</th> --}}
>>>>>>> 3f76386da3e2c5b280dbdeeabc1e30aa3244bbf7
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
                    
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $product->product_name }}</td>
<<<<<<< HEAD
                        <td>{{ $product->sku }}</td>
=======
                        <td>{{ $product->product_code }}</td>
                        {{-- <td>{{ $product->description }}</td> --}}
>>>>>>> 3f76386da3e2c5b280dbdeeabc1e30aa3244bbf7
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
