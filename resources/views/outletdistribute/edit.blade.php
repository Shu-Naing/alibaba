@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">List Distribute Product</h4>
            <div>
                
            </div>
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

        {!! Form::model($outletdistributes, ['method' => 'PATCH','class' => '', 'id' => 'outletdistributes','route' => ['outletdistribute.update', $outletdistributes->id]]) !!}
            @csrf
            <div class="p-4 rounded border shadow-sm mb-5">
                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        {!! Form::label('date', 'Date', array('class' => 'form-label')) !!}
                        {!! Form::date('date', $outletdistributes->date, array('class' => 'form-control', 'id'=>'date')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('reference_No', 'Reference No.', array('class' => 'form-label')) !!}
                        {!! Form::text('reference_No', $outletdistributes->reference_No, array('class' => 'form-control', 'id' => 'reference')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('status', 'Status', array('class' => 'form-label')) !!}
                        {!! Form::select('status', $ds_status, $outletdistributes->status, array('placeholder' => 'Choose to status', 'class' => 'form-control','id'=>'status')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('from_outlet', 'From (Outlet)', array('class' => 'form-label')) !!}
                        {!! Form::hidden('from_outlet', $outletdistributes->from_outlet) !!}
                        {!! Form::select('from_outlet_select',$outlets, $outletdistributes->from_outlet, array('placeholder' => 'Choose From outlets', 'class' => 'form-control','id'=>'fromOutlet', 'disabled' => 'disabled')) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('counterMachine', 'To (Counter / Machine)', array('class' => 'form-label')) !!}
                        {!! Form::select('counterMachine', $counter_machine, $outletdistributes->counter_machine, array('placeholder' => 'Choose', 'class' => 'form-control counterMachine','id'=>'counterMachine')) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('counter', 'Counter', array('class' => 'form-label')) !!}
                        {!! Form::select('toCounterMachine', $counter, ($outletdistributes->counter_machine === 1) ? $outletdistributes->to_machine : null, array('placeholder' => 'Choose', 'class' => 'form-control counter','id'=>'counter', 'disabled' => ($outletdistributes->counter_machine === 2) ? 'disabled' : false)) !!}
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('machine', 'Machine', array('class' => 'form-label')) !!}
                        {!! Form::select('toCounterMachine', $machines, ($outletdistributes->counter_machine === 2) ? $outletdistributes->to_machine : null, array('placeholder' => 'Choose', 'class' => 'form-control machine','id'=>'machine', 'disabled' => ($outletdistributes->counter_machine === 1) ? 'disabled' : false)) !!}
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-4">Add Products</h5>
            <div class="input-group rounded w-25 mb-3">
                <div>
                    <input type="hidden" id="outletdistribute_id" value="{{ $outletdistributes->id }}">
                    <input type="text" class="form-control" id="outletdistir_searchInput" data-id="{{ $outletdistributes->from_outlet }}" placeholder="Search...">
                    <div id="searchResults"></div>
                </div>
            </div>

            <?php  $total = 0; ?>
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
                                <!-- <td class="align-middle"> 6Pcs + -</td> -->
                                <td class="align-middle"> 
                                    <div class="qty-box border rounded">
                                        <div class="row gx-0">
                                            <div class="col">
                                                <div class="border p-2"><input type="number" class="number" min="1" value="{{$product->quantity}}" data-id="{{$product->id}}"></div>
                                            </div>
                                            <div class="col">
                                                <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="increaseOutletdisValue(this, {{$product->id}})" value="Increase Value">+</div>
                                            </div>
                                            <div class="col">
                                                <div class="value-button h-100 border d-flex align-items-center justify-content-center" onclick="decreaseOutletdisValue(this, {{$product->id}})" value="Decrease Value">-</div>
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
                    <h4 class="fw-bolder">Total Amount: <span id="total" class="ms-3 inline-block text-blue">{{$total}}</span></h4> 
                </div>
            </div>

            <div class="text-center my-5">
                <!-- <a class="btn btn-red" href="{{ url()->previous() }}">Back</a> -->
                <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
                <button type="submit" form="outletdistributes" class="btn btn-blue ms-2">Save</button>
            </div>
        {!! Form::close() !!}

        
    </div>

    

    

@endsection
