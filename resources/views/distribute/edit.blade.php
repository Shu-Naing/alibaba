@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Distribute Product</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::model($distribute, [
            'method' => 'PATCH',
            'class' => '',
            'id' => 'distribute',
            'route' => ['distribute.update', $distribute->id],
        ]) !!}
        @csrf
            <div class="p-4 rounded border shadow-sm mb-5">
                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        {!! Form::label('date', 'Date', ['class' => 'form-label']) !!}
                        {!! Form::date('date', $distribute->date, ['class' => 'form-control', 'id' => 'date']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('reference_No', 'Reference No.', ['class' => 'form-label']) !!}
                        {!! Form::text('reference_No', $distribute->reference_No, ['class' => 'form-control', 'id' => 'reference']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                        {!! Form::select('status', $ds_status, $distribute->status, [
                            'placeholder' => 'Choose to status',
                            'class' => 'form-control',
                            'id' => 'status',
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('from_outlet', 'From (Outlet)', ['class' => 'form-label']) !!}
                        {!! Form::select('from_outlet', $outlets, $distribute->from_outlet, [
                            'placeholder' => 'Choose From outlets',
                            'class' => 'form-control',
                            'id' => 'fromOutlet',
                        ]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('to_outlet', 'To (Outlet)', ['class' => 'form-label']) !!}
                        {!! Form::select('to_outlet', $outlets, $distribute->to_outlet, [
                            'placeholder' => 'Choose to outlets',
                            'class' => 'form-control',
                            'id' => 'toOutlet',
                        ]) !!}
                    </div>
                </div>
            </div>
           

            <h5 class="fw-bold mb-4">Add Products</h5>
            <div class="input-group rounded w-25 mb-3">
                <div>
                    <!-- <div class="" id="distributedId"></div> -->
                    <input type="hidden" id="distributedId" value="{{ $distribute->id }}">
                    <input type="text" class="form-control" id="searchInput" data-id="{{ $distribute->from_outlet }}" placeholder="Search...">
                    <div id="searchResults"></div>
                </div>

                <!-- <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                <span class="input-group-text border-0" id="search-addon">
                    <i class="bi bi-search"></i>
                </span> -->
            <!-- </div> -->

            <!-- <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
                    <span class="input-group-text border-0" id="search-addon">
                        <i class="bi bi-search"></i>
                    </span> -->
            </div>

            <?php $total = 0; ?>
            <div id="show_dsProduct">
                @foreach ($distribute_products as $product)
                    <?php
                    $subtotal = $product->purchased_price * $product->quantity;
                    $total += $subtotal;
                    ?>
                    <table class="table table-bordered text-center shadow rounded">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 30%;">Product Name</th>
                                <th scope="col">Item Code</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Purchased Price</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle" style="text-align: left;">
                                    {{ $product->product_name }}
                                </td>
                                <td class="align-middle text-center" style="text-align: left;">
                                    {{ $product->item_code }}
                                </td>
                                <!-- <td class="align-middle"> 6Pcs + -</td> -->
                                <td class="align-middle">
                                    <div class="qty-box border rounded">
                                        <div class="row gx-0">
                                            <div class="col">
                                                <div class="border p-2"><input type="number" class="number number-box" min="1" 
                                                        value="{{ $product->quantity }}" data-id="[{{ $product->id }}, {{ $product->variant_id }}, {{$variant_qty[$product->variant_id]}}]"></div>
                                            </div>
                                            <div class="col">
                                                <div class="value-button h-100 border d-flex align-items-center justify-content-center" id="increase-type" data-id="increase"
                                                    onclick="increaseValue(this, {{ $product->id }}, {{ $product->variant_id }}, {{$variant_qty[$product->variant_id]}})" value="Increase Value">+
                                                    <!-- <div id="loading-indicator">Loading...</div> -->
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="value-button h-100 border d-flex align-items-center justify-content-center" id="decrease-type" data-id="decrease"
                                                    onclick="decreaseValue(this, {{ $product->id }} , {{ $product->variant_id }})" value="Decrease Value">-
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">{{ $product->purchased_price }}</td>
                                <td class="align-middle">{{ $product->subtotal }}</td>
                                <td class="align-middle"><a href="javascript:void(0)" class="text-danger"
                                        onclick="deleteDisValue({{ $product->id }})">Delete</a></td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>

            <div class="d-flex gap-4 px-4 justify-content-end my-5">
                <div class="">
                    <!-- <label for="remark" class="d-block mb-2">Remark</label>
                            <textarea name="remark" id="" cols="40" rows="4"></textarea> -->
                    {!! Form::label('remark', 'Remark', ['class' => 'form-label']) !!}
                    {!! Form::textarea('remark', $distribute->remark, [
                        'class' => 'form-control',
                        'id' => 'remark',
                        'cols' => '40',
                        'rows' => '4',
                    ]) !!}
                </div>
                <div class="align-items-center d-flex">
                    <h4 class="fw-bolder">Total Amount: <span id="total"
                            class="ms-3 inline-block text-blue">{{ $total }}</span></h4>
                </div>
            </div>

            <div class="text-center my-5">
                <!-- <a class="btn btn-red" href="{{ url()->previous() }}">Back</a> -->
                <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
                <button type="submit" form="distribute" class="btn btn-blue ms-2">Save</button>
            </div>

        {!! Form::close() !!}
    </div>





@endsection
