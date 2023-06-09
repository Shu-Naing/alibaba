@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Show Product</h4>
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
                        <a href="{{ route('product.export') }}">Export</a>
                        {{-- <a href="{{ route('product.print') }}">Print</a> --}}
                        <button onclick="printDivContent()">Print</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="divCon">
            <table style="width: 100%;
            border-collapse: collapse;border: 1px solid;">
                <tr>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">ID</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Product Name</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Image</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Item Code</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Select</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Value</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Brand</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Cateogry</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Unit</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Purchased Price</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Point</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Ticket</th>
                    <th style="border: 1px solid;white-space: nowrap;
                    padding: 10px;">Kyat</th>
                </tr>
                @php 
                    $no = 1;
                @endphp
                @foreach ($products as $product)
                    <tr>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $no++ }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->product->product_name }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ asset('storage'. $product->image) }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->item_code }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->select }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->value }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->product->brand->brand_name }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->product->category->category_name }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->product->unit->name }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->purchased_price }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->points }}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{$product->tickets}}</td>
                        <td style="border: 1px solid;white-space: nowrap;
                        padding: 10px;">{{ $product->kyat }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        
    </div>

    
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