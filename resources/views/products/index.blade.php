@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">List Product</h4>
            <div>
            </div>
        </div>

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
                        <div class="col-lg-4 col-sm-12 d-flex mb-3 justify-content-end">
                            <a class="btn btn-blue" href="{{ route('products.create') }}">Add +</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Display the list of products -->
<h1>Products</h1>
<table id="table_id">
    <thead>
        <tr>
            <th>ID</th>
            <th>Item Code</th>
            <th>Image</th>
            <th>Product Name</th>
            <th>Points</th>
            <th>Tickets</th>
            <th>Kyat</th>
            <th>Received Qty</th>
            <th>Received Date</th>
            <th>Company Name</th>
            <th>Country</th>
            <th>Category</th>
            <th>Unit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->item_code }}</td>
            <td>{{ $product->image }}</td>
            <td>{{ $product->product->product_name }}</td>
            <td>{{ $product->points }}</td>
            <td>{{ $product->tickets }}</td>
            <td>{{ $product->kyat }}</td>
            <td>{{ $product->received_qty }}</td>
            <td>{{ $product->product->received_date }}</td>
            <td>{{ $product->product->company_name }}</td>
            <td>{{ $product->product->country }}</td>
            <td>{{ $product->product->category->category_name }}</td>
            <td>{{ $product->product->unit->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


    </div>
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
    </style>
@endsection
