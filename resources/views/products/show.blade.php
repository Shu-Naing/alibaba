@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4 d-print-none">
            <h4 class="fw-bolder mb-3">Show Product</h4>
            <div>
            </div>
        </div>
        <div class="row d-print-none">
            <div class="col-lg-12">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-lg-3 col-sm-12">
                            <input type="file" class="form-control">
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <button class="btn btn-primary">Submit</button>
                            <button class="btn btn-success">Download Template</button>
                        </div>
                        <div class="col-lg-5 col-sm-12 d-flex justify-content-end">
                            <button onclick="window.print()" class="btn btn-red me-2">Export to Excel</button>
                            <a href="{{ route('product.export') }}" class="btn btn-red me-2">Export to Excel</a>
                            <a class="btn btn-blue" href="{{ route('products.create') }}">Add +</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Item Code</th>
                    <th>Select</th>
                    <th>Value</th>
                    <th>Brand</th>
                    <th>Cateogry</th>
                    <th>Unit</th>
                    <th>Purchased Price</th>
                    <th>Point</th>
                    <th>Ticket</th>
                    <th>Kyat</th>
                </tr>
                @php
                    $no = 1;
                @endphp
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $product->product->product_name }}</td>
                        <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"></td>
                        <td>{{ $product->item_code }}</td>
                        <td>{{ $product->select }}</td>
                        <td>{{ $product->value }}</td>
                        <td>{{ $product->product->brand->brand_name }}</td>
                        <td>{{ $product->product->category->category_name }}</td>
                        <td>{{ $product->product->unit->name }}</td>
                        <td>{{ $product->purchased_price }}</td>
                        <td>{{ $product->points }}</td>
                        <td>{{ $product->tickets }}</td>
                        <td>{{ $product->kyat }}</td>
                    </tr>
                @endforeach
            </table>
        </div>


    </div>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid;
        }

        table img {
            width: 60px;
            height: 60px;
        }

        th,
        td {
            border: 1px solid;
            white-space: nowrap;
            padding: 10px;
        }
    </style>

    <script>
        function printDivContent() {
            var contentOfDiv = document.getElementById("divCon").innerHTML;
            var newWin = window.open('', '', 'height=1000, width=1000');
            newWin.document.write(contentOfDiv);
            newWin.document.write('');
            newWin.document.close();
            newWin.print();
        }
    </script>
@endsection
