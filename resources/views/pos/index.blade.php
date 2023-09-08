<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MiniCommerce') }}</title>

    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-3.2.6.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />

    @yield('links')
    @yield('styles')
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
</head>

<body>
    <div class="container-fluid pt-3">
        <div class="breadcrumbBox rounded mb-4 d-print-none">
            <h4 class="fw-bolder mb-3">Pos</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>

        {{-- <div>
            <input type="text" id="last-barcode" placeholder="Scan barcode">

        </div> --}}

        <div class="errorbox"></div>

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
                        @php 
                                $outlet_item_kyat =  outlet_item_data($outlet_item->outlet_id,$outlet_item->variation_id)->kyat ;
                                $outlet_item_points =  outlet_item_data($outlet_item->outlet_id,$outlet_item->variation_id)->points ;
                                $outlet_item_tickets =  outlet_item_data($outlet_item->outlet_id,$outlet_item->variation_id)->tickets ;
                                $outlet_item_quantity =  outlet_item_data($outlet_item->outlet_id,$outlet_item->variation_id)->quantity ;
                          
                            

                        @endphp
                        <div class="col-lg-4">
                            <div class="card p-2 border text-center">
                                <img src="{{ asset('storage/' . $outlet_item->variation->image) }}"
                                    alt="{{ $outlet_item->variation->item_code }}">
                                <span
                                    class="mt-2 pb-0 fw-bold" style="font-size: 18px;color: #000;">{{ $outlet_item->variation->product->product_name }}</span>
                                <small class="mb-3">{{ $outlet_item->variation->item_code }} . Size :
                                    {{ $outlet_item->variation->sizeVariant->value }}</small>
                                
                               
                                

                                <div class="fw-bolder d-flex justify-content-between mb-2">
                                    <small>
                                        @if ($outlet_item->variation->points != null || $outlet_item->variation->points != 0)
                                            Point : {{ $outlet_item->variation->points }}
                                        @endif
                                    </small>
                                    <small>
                                        @if ($outlet_item_kyat != null || $outlet_item_kyat != 0)
                                            {{$outlet_item_kyat  }} Kyat
                                        @endif    
                                    </small>
                                    <small>
                                        @if ($outlet_item->variation->tickets != null || $outlet_item->variation->tickets != 0)
                                            Ticket :
                                            {{ $outlet_item->variation->tickets }}
                                        @endif
                                    </small>
                                </div>
                                
                                
                                @if ($outlet_item_quantity == 0)
                                    <button class="btn btn-red btn-sm mt-2" disabled>Out Of Stock</button>
                                @else
                                    
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
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control qty" value="{{ $temp->quantity }}"
                                            data-temp-id="{{ $temp->id }}"
                                            data-outlet-item-stock="{{ outlet_item_data(Auth::user()->outlet_id,$temp->variation->id)->quantity }}"
                                            data-outlet-item-alert="{{ $temp->variation->alert_qty }}"
                                            min="1"
                                            max="{{ outlet_item_data(Auth::user()->outlet_id,$temp->variation->id)->quantity }}" id="quantityInput">

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
                    <div class="card  border py-3 @if (!session()->has('pos-success')) d-none @endif">
                       
                        <div class=" mx-auto" style="width: 40%;">
                            <img src="{{ asset('images/logo_1.png') }}" alt="" class="w-100">
                        </div>
                        
                        <div class="text-center mt-5">
                            <h3>{{ $outlet_name }} (STORE)</h2>
                            <div class="fw-bolder fs-5 my-3">Invoice</div>
                        </div>

                       

                        <div class="row px-5">
                            <hr class="dash">
                            <div class="col-lg-12 d-flex justify-content-between">
                                <div class="fw-bolder">Invoice No.</div>
                                <div>{{ $pos_items[0]->pos->invoice_no }}</div>
                            </div>
                            @php
                                 $formattedCreatedAt = \Carbon\Carbon::parse($pos_items[0]->pos->created_at)->format('m/d/Y h:i A');
                            @endphp
                            <div class="col-lg-12 d-flex justify-content-between">
                                <div class="fw-bolder">Date</div>
                                <div>{{ $formattedCreatedAt }}</div>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-between">
                                <div class="fw-bolder">Customer</div>
                                <div>Walk-In Customer</div>
                            </div>

                            <table class="pos-table mt-4">
                                <tr>
                                    <th style="text-align: start"># Product</th>
                                    <th style="text-align: center">QTY</th>
                                    <th style="text-align: center">Amount</th>
                                    <th style="text-align: end">Subtotal</th>
                                </tr>
                                @php 
                                    $pos_total = 0 ;
                                    $no = 1;
                                @endphp
                                @foreach ($pos_items as $pos_item)
                                    <tr class="dash">
                                        <td style="text-align: start">{{ $no++ }}   {{ $pos_item->variation->item_code }}</td>
                                        <td style="text-align: center">{{ $pos_item->quantity }}</td>
                                        <td style="text-align: center">{{ $pos_item->variation_value }}</td>
                                        <td style="text-align: end">{{ $pos_item->variation_value * $pos_item->quantity }}</td>
                                    </tr>
                                    @php $pos_total += $pos_item->variation_value * $pos_item->quantity @endphp
                                @endforeach
                                <tr class="dash" style="text-align: end;">
                                    <td colspan="2">Total Qty:</td>
                                    <td colspan="2">{{ count($pos_items) }}</td>
                                </tr>
                                <tr style="text-align: end;">
                                    <td colspan="2">SubTotal:</td>
                                    <td colspan="2">{{ $pos_total }}</td>
                                </tr>
                                <tr style="text-align: end;">
                                    <td colspan="2">Total:</td>
                                    <td colspan="2">{{ $pos_total }}</td>
                                </tr>
                                <tr style="text-align: end;">
                                    <td colspan="2">{{ ucwords(request()->get('filter')) }}:</td>
                                    <td colspan="2">{{ $pos_total }}</td>
                                </tr>
                                <tr style="text-align: end;">
                                    <td colspan="2">Total Paid:</td>
                                    <td colspan="2">{{ $pos_total }}</td>
                                </tr>
                                <tr class="dash">
                                    <td colspan="4">Thank you for coming to us.</td>
                                </tr>
                            </table>
                        </div>
                        {{-- <hr class="dash">
                        <div>
                            Thank you for coming to us.
                        </div> --}}
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.6/dist/notiflix-aio-3.2.6.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/auto-complete.js') }}"></script>
    <script src="{{ asset('js/pos.js') }}"></script>

    <script>
        $(document).ready(function() {

           


            // Update Item
            $('.qty').on('change keydown keyup', function(event) {
                if (event.type === 'keydown' && event.key !== 'Enter') {
                    return; // Ignore keydown events that are not the Enter key
                }

                var qty = $(this).val();
                var temp_id = $(this).data('temp-id');
                var outlet_item_stock = $(this).data('outlet-item-stock');
                var outlet_item_alert = $(this).data('outlet-item-alert');

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
                        // location.href = location.href;
                        location.reload();
                        console.log(response);
                    },
                    error: function(xhr) {
                        // Handle the error response
                        console.log(xhr.responseText);
                    }
                });

                // alert(outlet_item_stock);
                // alert(qty);
                // alert(outlet_item_alert);
                if((outlet_item_stock - qty) <= outlet_item_alert) {
                    $.ajax({
                        url: "{{ route('positem.alert') }}",
                        type: "POST",
                        data: {
                            temp_id,
                            outlet_item_alert,
                        },
                        success: function(response) {
                            localStorage.setItem('message', response.message);
                            // Handle the success response
                            location.href = location.href;
                            // console.log(response);
                            // alert(`${response.item_code} is under ${outlet_item_alert}`);
                            // alert(response.message);
                        },
                        error: function(xhr) {
                            // Handle the error response
                            console.log(xhr.responseText);
                        }
                    });
                    // alert('your item is under');
                }
            });

            if (localStorage.getItem('message')) {
                // Get the message from localStorage
                var message = localStorage.getItem('message');
                // Display the message in the designated element
                $('.errorbox').append(`Warning: ${message} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`).addClass("alert alert-warning alert-dismissible fade show");
                // Clear the stored message from localStorage
                localStorage.removeItem('message');
            }

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
</body>

</html>
