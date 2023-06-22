@extends('layouts.app')
@section('styles')
    <style>
        th,
        td {
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }

        table .tb-header-blue {
            background-color: blue;
            color: white;
        }

        table img {
            width: 60px;
            height: 60px;
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
            <h4 class="fw-bolder mb-3">Report Product</h4>
            <div>
            </div>
        </div>


        <div class="my-3">
            <a href="{{ route('product.export') }}" class="btn btn-red me-2">Export to Excel</a>
        </div>

        <table class="table  table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Photo</th>
                    <th>Product Name</th>
                    <th>Point</th>
                    <th>Ticket</th>
                    <th>Price (WS)</th>
                    <th>Size</th>
                    <th>Received Date</th>
                    <th>Company Name</th>
                    <th>Country</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>UOM</th>
                    <th>Inventory <br> Store Balance</th>
                    @foreach ($outlets as $outlet)
                        <th>{{ $outlet->name }}<br> Store Balance</th>
                        @foreach ($outlet->machines as $machine)
                            <th>{{ $machine->name }}<br> Machine Balance</th>
                        @endforeach
                    @endforeach
                    <th>Total <br> Store Balance</th>
                    <th>Total <br> Machine Balance</th>
                    <th class="tb-header-blue">Grand <br> Total Balance</th>
                    <th class="tb-header-blue">Grand <br> Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>{{ $report->item_code }}</td>
                        <td><img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->product->product_name }}">
                        </td>
                        <td>{{ $report->product->product_name }}</td>
                        <td>{{ !isset($report->points) || $report->points == 0 ? 0 : $report->points }}</td>
                        <td>{{ !isset($report->tickets) || $report->tickets == 0 ? 0 : $report->tickets }}</td>
                        <td>{{ !isset($report->kyat) || $report->kyat == 0 ? 0 : $report->kyat }}</td>
                        <td>{{ $report->value }}</td>
                        <td>{{ $report->product->received_date }}</td>
                        <td>{{ $report->product->company_name }}</td>
                        <td>{{ $report->product->country }}</td>
                        <td>{{ $report->product->category->category_name }}</td>
                        <td>{{ $report->product->brand->brand_name }}</td>
                        <td>{{ $report->product->unit->name }}</td>
                        <td>{{ outlet_stock($report->id) }}</td>
                        @foreach ($outlets as $outlet)
                            @php  $store_balance = outlet_stock($report->id, $outlet->id); @endphp
                            <td>
                                {{ !isset($store_balance) || $store_balance == 0 ? 0 : $store_balance }}
                            </td>
                            @foreach ($outlet->machines as $machine)
                                @php  $machine_balance = machine_stock($report->id, $machine->id); @endphp
                                <td>
                                    {{ !isset($machine_balance) || $machine_balance == 0 ? 0 : $machine_balance }}
                                </td>
                            @endforeach
                        @endforeach
                        <td>{{ total_store_stock($report->id) }}</td>
                        <td>{{ total_machine_stock($report->id) }}</td>
                        <td>{{ total_store_stock($report->id) + total_machine_stock($report->id) }}</td>
                        <td>{{ $report->kyat * (total_store_stock($report->id) + total_machine_stock($report->id)) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
