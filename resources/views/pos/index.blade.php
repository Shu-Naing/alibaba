@extends('layouts.app')
@section('styles')
    <style>
        .pos-item img {
            width: 60px;
            height: 60px;
        }

        .dash {
            border-top: 1px dashed gray;
        }

        .text-red {
            color: var(--primary-color);
        }

        .pos-table td,
        th {
            padding: 3px 0px;
            text-align: center;
        }

        .pos-total td {
            text-align: center;
        }

        .barcode-active{
            color: red;
        }

        .barcode-icon i{
           font-size: 30px;
        }

        @media print {
            body .card{
                height: 100%;
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        margin: 0;
            }
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
        <div class="breadcrumbBox rounded mb-4 d-print-none">
            <h4 class="fw-bolder mb-3">Pos</h4>
            <div>
            </div>
        </div>

        {{-- <div>
            <input type="text" id="last-barcode" placeholder="Scan barcode">

        </div> --}}

        <div class="row">
            <div class="col-lg-7 d-print-none">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card p-3">
                            <div class="d-flex justify-content-between form-outline">
                                <input type="search" class="form-control w-50" placeholder="Search Product" aria-label="Search"
                                    id="searchInput" name="key" @if (!request()->has('filter') || session()->has('pos-success')) disabled @endif />
                                    <div class="barcode-icon">
                                        <i class="bi bi-upc-scan" id="barcode-icon"></i>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($outlet_items as $outlet_item)
                        <div class="col-lg-4">
                            <div class="card p-2 border text-center">
                                <img src="{{ asset('storage/' . $outlet_item->variation->image) }}"
                                    alt="{{ $outlet_item->variation->item_code }}">
                                <span
                                    class="fw-bolder mt-2 pb-0">{{ $outlet_item->variation->product->product_name }}</span>
                                <small class="fw-bolder">[{{ $outlet_item->variation->item_code }}]</small>
                                <small class="fw-bolder">{{ $outlet_item->variation->select }} :
                                    {{ $outlet_item->variation->value }}</small>
                                @if ($outlet_item->variation->kyat != null || $outlet_item->variation->kyat != 0)
                                    <small class="fw-bolder">Kyat :
                                        {{ $outlet_item->variation->kyat }}</small>
                                @endif

                                <small class="fw-bolder">
                                    @if ($outlet_item->variation->points != null || $outlet_item->variation->points != 0)
                                        Point : {{ $outlet_item->variation->points }}
                                    @endif
                                    @if ($outlet_item->variation->tickets != null || $outlet_item->variation->tickets != 0)
                                        ,Ticket :
                                        {{ $outlet_item->variation->tickets }}
                                    @endif
                                </small>
                                @if ($outlet_item->quantity == 0)
                                    <button class="btn btn-red btn-sm mt-2" disabled>Out Of Stock</button>
                                @else
                                    {{-- <button class="btn btn-red btn-sm mt-2 add-pos-btn"
                                        data-variation-id="{{ $outlet_item->variation->id }}"
                                        @if (request()->get('filter') === 'point') data-variation-value="{{ $outlet_item->variation->points }}" @endif
                                        @if (request()->get('filter') === 'ticket') data-variation-value="{{ $outlet_item->variation->tickets }}" @endif
                                        @if (request()->get('filter') === 'kyat') data-variation-value="{{ $outlet_item->variation->kyat }}" @endif
                                        @if (!request()->has('filter') || session()->has('pos-success')) disabled @endif>Added</button> --}}
                                        <button class="btn btn-red btn-sm mt-2 add-pos-btn"
                                        data-variation-id="{{ $outlet_item->variation->id }}"
                                        @if (request()->get('filter') === 'points') data-payment-type="points" @endif
                                        @if (request()->get('filter') === 'tickets') data-payment-type="tickets" @endif
                                        @if (request()->get('filter') === 'kyat') data-payment-type="kyat" @endif
                                        @if (!request()->has('filter') || session()->has('pos-success')) disabled @endif>Added</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border @if (session()->has('pos-success')) d-none @endif d-print-none">
                    <div class="card-header fw-bolder d-flex justify-content-between align-items-center">
                        <div>New Invoice</div>{{ session('payment_type') }}
                        <select id="productFilter" class="form-select w-50" aria-label="Default select example"
                            @if (count($temps) > 0) disabled @endif>
                            <option value="{{ URL::current() }}" selected>Choose Payment</option>
                            <option value="{{ URL::current() }}?filter=points"
                                @if (request()->get('filter') === 'points') selected @endif>Point</option>
                            <option value="{{ URL::current() }}?filter=tickets"
                                @if (request()->get('filter') === 'tickets') selected @endif>Ticket</option>
                            <option value="{{ URL::current() }}?filter=kyat"
                                @if (request()->get('filter') === 'kyat') selected @endif>Kyat</option>
                        </select>
                        <div><button class="remove-pos-btn btn btn-sm btn-primary" data-temp-id="all">Clear</button></div>
                    </div>
                    <div class="card-body">
                        @php $total=0; @endphp
                        @foreach ($temps as $temp)
                            <div class="card mb-3 pos-item p-2 border" id="product-list">
                                <div class="row g-0 d-flex align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ asset('storage/' . $temp->variation->image) }}"
                                            class="img-fluid rounded-start" alt="...">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-center">
                                            <span
                                                class="fw-bolder d-block">{{ $temp->variation->product->product_name }}</span>
                                            <small class="fw-bold d-block">[{{ $temp->variation->item_code }}]</small>
                                            @if (request()->get('filter') === 'kyat')
                                                <small class="fw-bold">Kyat : {{ $temp->variation->kyat }}</small>
                                            @elseif(request()->get('filter') === 'points')
                                                <small class="fw-bold">Point : {{ $temp->variation->points }}</small>
                                            @elseif(request()->get('filter') === 'tickets')
                                                <small class="fw-bold">Ticket : {{ $temp->variation->tickets }}</small>
                                            @endif
                                            {{-- <small class="fw-bold">Point : {{ $temp->variation->points }},Ticket :
                                                {{ $temp->variation->tickets }}</small> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control qty" value="{{ $temp->quantity }}"
                                            data-temp-id="{{ $temp->id }}"
                                            data-outlet-item-stock="{{ outlet_stock($temp->variation->id, Auth::user()->outlet->id) }}"
                                            min="1"
                                            max="{{ outlet_stock($temp->variation->id, Auth::user()->outlet->id) }}">

                                    </div>
                                    <div class="col-md-1 text-center">
                                        <i class="bi bi-trash text-primary fs-6 remove-pos-btn"
                                            data-temp-id="{{ $temp->id }}"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- Calculate Pos Total Section --}}
                            @php
                                if (request()->has('filter')) {
                                    if (request()->get('filter') === 'points') {
                                        $item_price = $temp->variation->points * $temp->quantity;
                                    } elseif (request()->get('filter') === 'tickets') {
                                        $item_price = $temp->variation->tickets * $temp->quantity;
                                    } elseif (request()->get('filter') === 'kyat') {
                                        $item_price = $temp->variation->kyat * $temp->quantity;
                                    } else {
                                        $item_price = 0;
                                    }
                                
                                    $total += $item_price;
                                }
                                
                            @endphp
                            {{-- End Calculate Pos Total Section --}}
                        @endforeach
                        <hr class="dash">


                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-between">
                                <span class="fw-bolder fs-4">Total</span>
                                <span class="fw-bolder fs-3">{{ $total }} {{ request()->get('filter') }}s</span>
                            </div>
                            <div class="col-md-12 mt-4">
                                <button class="btn btn-red w-100 add-pos" @if (!request()->has('filter')) disabled @endif
                                    @if (count($temps) == 0) disabled @endif data-total="{{ $total }}"
                                    data-payment="{{ request()->get('filter') }}">Place
                                    Order</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Invoice --}}
                @if (count($pos_items) != 0)
                    <div class="card border py-3 @if (!session()->has('pos-success')) d-none @endif">
                        <h2 class="text-center fw-bolder m-0 p-0">Alibaba</h2>
                        <h6 class="text-center fw-bolder">Amusement</h6>
                        <div class="text-center">
                            <span class="fw-bold d-block">Invoice No : {{ $pos_items[0]->pos->invoice_no }}</span>
                            <span class="fw-bold d-block">Payment : {{ $pos_items[0]->pos->payment_type }}</span>
                            <span class="fw-bold d-block">Date : {{ date('d-m-y') }}</span>
                        </div>
                        <hr class="dash">
                        <table class="pos-table">
                            <tr>
                                <th>Item Code</th>
                                <th>QTY</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            @php $pos_total = 0 ;@endphp
                            @foreach ($pos_items as $pos_item)
                                <tr>
                                    <td>{{ $pos_item->variation->item_code }}</td>
                                    <td>{{ $pos_item->quantity }}</td>
                                    <td>{{ $pos_item->variation_value }}</td>
                                    <td>{{ $pos_item->variation_value * $pos_item->quantity }}</td>
                                </tr>
                                @php $pos_total += $pos_item->variation_value * $pos_item->quantity @endphp
                            @endforeach
                            <tr class="dash">
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td>{{ $pos_total }}</td>
                            </tr>
                        </table>
                    </div>
                    @if (session()->has('pos-success'))
                        <div class="row d-flex d-print-none">
                            <div class="col-md-6">
                                <button onclick="window.print()" class="btn btn-red w-100">Print</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('pos.index') }}" class="btn btn-primary w-100">Back To Pos</a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>




    <script src="{{ asset('js/pos.js') }}"></script>

@section('scripts')
    <script>
        $(document).ready(function() {

           


            // Update Item
            $('.qty').on('change', function() {
                var qty = $(this).val();
                var temp_id = $(this).data('temp-id');
                var outlet_item_stock = $(this).data('outlet-item-stock');
                if (qty > outlet_item_stock) {
                    qty = outlet_item_stock;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('positem.update') }}",
                    type: "POST",
                    data: {
                        qty: qty,
                        temp_id: temp_id
                    },
                    success: function(response) {
                        // Handle the success response
                        location.href = location.href;
                        console.log(response);
                    },
                    error: function(xhr) {
                        // Handle the error response
                        console.log(xhr.responseText);
                    }
                });
            });


            // Remove Item
            $('.remove-pos-btn').click(function(e) {
                e.preventDefault();
                var temp_id = $(this).data('temp-id');

                $.ajax({
                    type: 'DELETE',
                    url: '{{ route('positem.remove') }}',
                    data: {
                        temp_id: temp_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        location.href = '{{ route('pos.index') }}';
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while adding the product to cart');
                        console.log(xhr.responseText);
                    }
                });
            });

        });


        $('.add-pos').click(function(e) {
            e.preventDefault();
            var total = $(this).data('total');
            var payment_type = $(this).data('payment');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '{{ route('pos.add') }}',
                data: {
                    total: total,
                    payment_type: payment_type,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.href = location.href;
                    console.log(response);
                   
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while adding the product to cart');
                    console.log(xhr.responseText);
                }
            });
        });



        $('#productFilter').change(function() {
            var selectedValue = $(this).val();
            console.log(selectedValue);
            if (selectedValue !== "") {
                // $('.add-pos-btn').removeAttr("disabled");
                window.location.href = selectedValue;
            }
        });


        

       
    </script>
@endsection
@endsection
