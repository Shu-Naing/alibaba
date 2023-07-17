@extends('layouts.app')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Outlet Distribute</h4>
        </div>
         @if(Session::has('success'))
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
        {!! Form::open(array('route' => 'outletdistribute.store', 'method' => 'post', 'class' => 'p-4 rounded border shadow-sm mb-5', 'id' => 'outletdistribute')) !!}
            @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    {!! Form::label('date', 'Date', array('class' => 'form-label')) !!}
                    {!! Form::date('date', null, array('class' => 'form-control', 'id'=>'date')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('reference_No', 'Reference No.', array('class' => 'form-label')) !!}
                    {!! Form::text('reference_No', $generatedRef, array('class' => 'form-control', 'id' => 'reference')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('status', 'Status', array('class' => 'form-label')) !!}
                    {!! Form::select('status', $ds_status, null, array('placeholder' => 'Choose to status', 'class' => 'form-control','id'=>'status')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('from_outlet', 'From (Outlet)', array('class' => 'form-label')) !!}
                    {!! Form::hidden('from_outlet', $id) !!}
                    {!! Form::select('from_outlet_select',$outlets, $id, array('placeholder' => 'Choose From outlets', 'class' => 'form-control','id'=>'fromOutlet', 'disabled' => 'disabled')) !!}
                </div>
                <div class="col-md-4">
                    {!! Form::label('counterMachine', 'To (Counter / Machine)', array('class' => 'form-label')) !!}
                    {!! Form::select('counterMachine', $counter_machine, null, array('placeholder' => 'Choose', 'class' => 'form-control counterMachine','id'=>'counterMachine')) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('counter', 'Counter', array('class' => 'form-label')) !!}
                    {!! Form::select('to_counter', $counter, null, array('placeholder' => 'Choose', 'class' => 'form-control counter','id'=>'counter', 'disabled')) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('machine', 'Machine', array('class' => 'form-label')) !!}
                    {!! Form::select('to_machine', $machines, null, array('placeholder' => 'Choose', 'class' => 'form-control machine','id'=>'machine', 'disabled')) !!}
                </div>
            </div>
        {!! Form::close() !!}

        <h5 class="fw-bold mb-4">Add Products</h5>
        <div class="input-group rounded w-25 mb-3">
            <div>
                <input type="hidden" id="outletdistribute_id" value="">
                <input type="text" class="form-control" id="outletdistir_searchInput" data-id="" placeholder="Search...">
                <div id="searchResults"></div>
            </div>
        </div>

        <div id="show_dsProduct">
                @foreach($outlet_distribute_products as $product)
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="align-middle" style="text-align: left;">
                                    {{$product->product_name}}
                                </td>

                                <td class="align-middle" style="text-align: left;">
                                    {{$product->item_code}}
                                </td>
                                
                                <td class="align-middle"> 
                                    <div class="qty-box border rounded">
                                        <div class="row gx-0">
                                            <div class="col">
                                                <div class="border p-2"><input type="number" class="number outlet-number-box" min="1" max="{{outlet_stock($product->variant_id,$outletdistributes->from_outlet)}}" value="{{$product->quantity}}" data-id="[{{ $product->id }}, {{ $product->variant_id }}, {{$variant_qty[$product->variant_id]}}]"></div>
                                            </div>
                                            <div class="col">
                                                <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseOutletdisValue(this,{{$product->id}}, {{$product->variant_id}}, {{$variant_qty[$product->variant_id]}})" value="Increase Value">+</div>
                                            </div>
                                            <div class="col">
                                                <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseOutletdisValue(this,{{$product->id}}, {{$product->variant_id}})" value="Decrease Value">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">{{$product->purchased_price}}</td>
                                <td class="align-middle">{{$product->subtotal}}</td>
                                <td class="align-middle" ><a href="javascript:void(0)" class="text-danger" onclick="deleteOutDisValue({{$product->id}})">Delete</a></td>
                            </tr>
                        </tbody>
                    </table>
                @endforeach
            </div>

        <div class="row justify-content-end my-5">
            <div class="col-md-4">
                <!-- <label for="remark" class="d-block mb-2">Remark</label>
                <textarea name="remark" id="" cols="40" rows="4"></textarea> -->
                {!! Form::label('remark', 'Remark', array('class' => 'form-label')) !!}
                {!! Form::textarea('remark', null, array('class' => 'form-control','id'=>'remark', 'cols' => '40', 'rows' => '4')) !!}
            </div>
            <div class="col-md-4 align-items-center d-flex">
                <h4 class="fw-bolder">Total Amount: <span id="total" class="ms-3 inline-block text-blue">0</span></h4> 
            </div>
        </div>

        <div class="text-center my-5">
            <a class="btn btn-red" href="{{ url()->previous() }}">Back</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" form="outletdistribute" class="btn btn-blue ms-2">Next</button>
        </div>
    </div>
    


@endsection
