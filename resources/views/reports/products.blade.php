@extends('layouts.app')
@section('styles')
    <style>
        th,
        td {
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }
        table .tb-header-red {
            background-color: var(--primary-color);
            color: white;
        }
        table img {
            width: 60px;
            height: 60px;
        }
        input[type='search'],label{
           margin-bottom:5px;
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
                @include('breadcrumbs')
            </div>
        </div>

        {!! Form::open([
            'route' => 'product.search',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-2">
                    {!! Form::label('received_date', 'Received Date', ['class' => 'form-label']) !!}
                    {{ Form::date('received_date', null, ['class' => 'form-control']) }}
    
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('product.reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('product.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}        
        <div class="table-responsive sticky-div">
            <table class="thead-primary" id="table_id">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item Code</th>
                        <th>Photo</th>
                        <th>Point</th>
                        <th>Ticket</th>
                        <th>Kyat</th>
                        <th>Product Name</th>
                        <th>Size Variant</th>
                        <th>Received Date</th>
                        <th>Company Name</th>
                        <th>Country</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>UOM</th>
                        <th>Purchased Price</th>
                        <th>Inventory <br> Store Balance</th>
                        <th>Total <br> Price</th>
                        @foreach ($outlets as $outlet)
                            <th>{{ $outlet->name }} Store <br> Balance</th>
                            <th>{{ $outlet->name }} Machine <br> Balance</th>
                            <th>Total <br> Price</th>
                        @endforeach
                        <th>Grand <br> Total Balance</th>
                        <th>Grand <br> Total Price</th>
                        <th>Total <br> Store Balance</th>
                        <th>Total <br> Machine Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->item_code }}</td>
                            <td><img src="{{ asset('storage/' . $report->image) }}"
                                    alt="{{ $report->product->product_name }}"></td>
                            <td>
                                {{ !isset($report->points) || $report->points == 0 ? 0 : $report->points }}</td>
                            <td>
                                {{ !isset($report->tickets) || $report->tickets == 0 ? 0 : $report->tickets }}</td>
                            <td>
                                {{ !isset($report->kyat) || $report->kyat == 0 ? 0 : $report->kyat }}</td>
                            <td>{{ $report->product->product_name }}</td>
                            <td>{{ isset($report->sizeVariant->value) ? $report->sizeVariant->value : '' }}</td>
                            <td>{{ $report->product->received_date }}</td>
                            <td>{{ $report->product->company_name }}</td>
                            <td>{{ $report->product->country }}</td>
                            <td>{{ $report->product->category->category_name }}</td>
                            <td>{{ $report->product->brand->brand_name }}</td>
                            <td>{{ $report->product->unit->name }}</td>
                            <td>{{ $report->purchased_price }}</td>
                            <td>{{ outlet_stock($report->id) }}</td>
                            <td>{{ outlet_stock($report->id) * $report->purchased_price }}</td>
                            @foreach ($outlets as $outlet)
                                @php  $store_balance = outlet_stock($report->id, $outlet->id); @endphp
                                <td>
                                    {{ !isset($store_balance) || $store_balance == 0 ? 0 : $store_balance }}
                                </td>
                                @php $machine_balance = oultet_total_machine_stock($report->id, $outlet->id); @endphp
                                <td>
                                    {{ !isset($machine_balance) || $machine_balance == 0 ? 0 : $machine_balance }}
                                </td>
                                @php $outlet_total_price = ($store_balance + $machine_balance) * $report->purchased_price @endphp
                                <td>
                                    {{ !isset($outlet_total_price) || $outlet_total_price == 0 ? 0 : $outlet_total_price }}
                                </td>
                                {{-- @foreach ($outlet->machines as $machine)
                               @php  $machine_balance = machine_stock($report->id, $machine->id); @endphp
                               <td>
                                   {{ !isset($machine_balance) || $machine_balance == 0 ? 0 : $machine_balance }}
                               </td>
                           @endforeach --}}
                            @endforeach
                            <td>
                                {{ outlet_stock($report->id) + total_store_stock($report->id) + total_machine_stock($report->id) }}
                            </td>
                            <td>
                                {{ $report->purchased_price * (outlet_stock($report->id) + total_store_stock($report->id) + total_machine_stock($report->id)) }}
                            </td>
                            <td>{{ total_store_stock($report->id) }}</td>
                            <td>{{ total_machine_stock($report->id) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


