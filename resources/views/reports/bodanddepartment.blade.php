@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Distribute Detail</h4>
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
        @if (Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif   

        @php
            $from_outlet = session()->get(PD_FROMOUTLET_FILTER);
            $to_outlet = session()->get(PD_TOOUTLET_FILTER);
            $itemcode = session()->get(PD_ITEMCODE_FILTER);
            $from_date = session()->get(PD_FROMDATE_FILTER);
            $to_date = session()->get(PD_TODATE_FILTER);
            $size_variant = session()->get(PD_SIZEVARIANT_FILTER);
            $purchase_price = session()->get(PD_PURCHASEPRICE_FILTER);
            $vouncher_no = session()->get(PD_VOUNCHERNO_FILTER);
        @endphp
        
        {!! Form::open([
            'route' => 'search-bodanddepartment',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-2">
                    {!! Form::label('fromOutlet', 'From Outlet', ['class' => 'form-label']) !!}
                    {!! Form::select('fromOutlet',$outlets, $from_outlet, ['placeholder'=>'Choose..','class' => 'form-control', 'id' => 'fromOutlet']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('toOutlet', 'To Outlet', ['class' => 'form-label']) !!}
                    {!! Form::select('toOutlet', $tooutlets, $to_outlet, ['placeholder'=>'Choose..','class' => 'form-control', 'id' => 'toOutlet']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('itemCode', 'Item Code', ['class' => 'form-label']) !!}
                    {!! Form::text('itemCode', $itemcode, ['placeholder'=>'Enter here...', 'class' => 'form-control', 'id' => 'itemCode']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('fromDate', 'From Date', ['class' => 'form-label']) !!}
                    {{ Form::date('fromDate', $from_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('toDate', 'To Date', ['class' => 'form-label']) !!}
                    {{ Form::date('toDate', $to_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('sizeVariant', 'Size Variant', ['class' => 'form-label']) !!}
                    {!! Form::select('sizeVariant', $sizeVariants, $size_variant, ['placeholder'=>'Choose..','class' => 'form-control', 'id' => 'sizeVariant']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('purchasePrice', 'Purchase Price', ['class' => 'form-label']) !!}
                    {!! Form::text('purchasePrice', $purchase_price, ['placeholder'=>'Enter here...', 'class' => 'form-control', 'id' => 'purchasePrice']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('vouncherNo', 'Vouncher No', ['class' => 'form-label']) !!}
                    {!! Form::text('vouncherNo', $vouncher_no, ['placeholder'=>'Enter here...', 'class' => 'form-control', 'id' => 'vouncherNo']) !!}
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('bodanddepartment-reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('bodanddepartment-export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}


        {{-- {!! Form::open([
            'route' => 'search-date-distribute-detail',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
        <div class="row mb-3 g-3">
            
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-blue ms-2">Search</button>
                <a href="{{route('search-date-reset')}}" class="btn btn-blue ms-2">Reset</a>
            </div>
        </div>

        {!! Form::close() !!} --}}

            <table class="table table-bordered text-center shadow rounded mb-3" id="table_lsdd">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Ref:</th>
                        <th scope="col">Vouncher No</th>
                        <th scope="col">From Outlet</th>
                        <th scope="col">To Outlet</th>
                        <th scope="col">Item Code</th>
                        <th scope="col">Photo</th>
                        <th scope="col">Size Variant</th>
                        <th scope="col">Purchase Price:</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Remark</th>
                        <th scope="col">Sub Total</th>
                        <th scope="col">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distributes as $distribute)
                        <tr>
                            <td>{{$distribute->date}}</td>
                            <td>{{$distribute->reference_No}}</td>
                            <td>{{$distribute->vouncher_no}}</td>
                            <td>{{isset($outlets[$distribute->from_outlet]) ? $outlets[$distribute->from_outlet] : ''}}</td>
                            <td>{{isset($tooutlets[$distribute->to_outlet]) ? $tooutlets[$distribute->to_outlet] : '' }}</td>
                            <td>{{$distribute->item_code}}</td>
                            <td><img class="product-img" src="{{ asset('storage/' . $distribute->image) }}" alt="{{ $distribute->image }}"></td>
                            <td>{{$distribute->value}}</td>
                            <td>{{$distribute->purchased_price}}</td>                           
                            <td>{{$distribute->quantity}}</td>
                            <td>{{$distribute->remark}}</td>
                            <td>{{$distribute->subtotal}}</td>
                            <td>{{$distribute->remark}}</td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
@endsection
