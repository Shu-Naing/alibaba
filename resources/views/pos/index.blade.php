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
            <h4 class="fw-bolder mb-3">Pos</h4>
            <div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card p-3">
                            <select id="productFilter" class="form-select w-50" aria-label="Default select example"
                                @if (count($temps) > 0) disabled @endif>
                                <option value="{{ URL::current() }}" selected>Filter Products</option>
                                <option value="{{ URL::current() }}?filter=point"
                                    @if (request()->get('filter') === 'point') selected @endif>Point</option>
                                <option value="{{ URL::current() }}?filter=ticket"
                                    @if (request()->get('filter') === 'ticket') selected @endif>Ticket</option>
                                <option value="{{ URL::current() }}?filter=kyat"
                                    @if (request()->get('filter') === 'kyat') selected @endif>Kyat</option>
                            </select>
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
                                @if ($outlet_item->variation->kyat != null)
                                    <small class="fw-bolder">Kyat :
                                        {{ $outlet_item->variation->kyat }}</small>
                                @endif

                                <small class="fw-bolder">
                                    @if ($outlet_item->variation->points != null)
                                        Point : {{ $outlet_item->variation->points }}
                                    @endif
                                    @if ($outlet_item->variation->tickets != null)
                                        ,Ticket :
                                        {{ $outlet_item->variation->tickets }}
                                    @endif
                                </small>
                                <button class="btn btn-red btn-sm mt-2 add-pos-btn"
                                    data-variation-id="{{ $outlet_item->variation->id }}"
                                    @if (!request()->has('filter') || $outlet_item->quantity == 0) disabled @endif>Added</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border">
                    <div class="card-header fw-bolder d-flex justify-content-between align-items-center">
                        <div>New Invoice</div>{{ session('payment_type') }}
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
                                            <small class="fw-bold">Point : {{ $temp->variation->points }},Ticket :
                                                {{ $temp->variation->tickets }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" value="{{ $temp->quantity }}">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <i class="bi bi-trash text-primary fs-6 remove-pos-btn"
                                            data-temp-id="{{ $temp->id }}"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- Calculate Pos Total Section --}}
                            @php
                                if (request()->has('filter')) {
                                    if (request()->get('filter') === 'point') {
                                        $item_price = $temp->variation->points * $temp->quantity;
                                    } elseif (request()->get('filter') === 'ticket') {
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
                                <button class="btn btn-red w-100" @if (!request()->has('filter')) disabled @endif>Place
                                    Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






@section('scripts')
    <script>
        $(document).ready(function() {


            $('.add-pos-btn').click(function(e) {
                e.preventDefault();

                var variation_id = $(this).data('variation-id');

                console.log(variation_id);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: '{{ route('positem.add') }}',
                    data: {
                        variation_id: variation_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // $("#product-list").load("partial.html #specificDiv");
                        location.href = location.href;
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while adding the product to cart');
                        console.log(xhr.responseText);
                    }
                });


            });


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
                        location.href = location.href;
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while adding the product to cart');
                        console.log(xhr.responseText);
                    }
                });
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
