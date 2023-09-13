@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Outlet Level History</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>

        @php
            $outlet_id = session()->get(OUTLET_LEVEL_HISTORY_FILTER);
            $from_date = session()->get(OUTLET_LEVEL_HISTORY_FROM_DATE_FILTER);
            $to_date = session()->get(OUTLET_LEVEL_HISTORY_TO_DATE_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'outletlevelhistory.search',
            'method' => 'post',
            'class' => 'p-4 rounded border shadow-sm mb-5',
        ]) !!}  
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-2">
                    {!! Form::label('fromDate', 'From Date', ['class' => 'form-label']) !!}
                    {{ Form::date('fromDate', $from_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('toDate', 'To Date', ['class' => 'form-label']) !!}
                    {{ Form::date('toDate', $to_date, ['class' => 'form-control']) }}
                </div>
                <div class="col-md-3">
                    {!! Form::label('outlet_id', 'Outlet', ['class' => 'form-label']) !!}
                    {{ Form::select('outlet_id', $outlets, $outlet_id, ['placeholder' => 'Choose...', 'class' => 'form-control']) }}
    
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('outletlevelhistory.reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <a href="{{ route('outletlevelhistory.export') }}" class="btn btn-blue ms-2">Export to Excel</a>
                </div>
            </div>
        {!! Form::close() !!}

        <table id="table_id">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Outlet</th>
                    <th>Date</th>
                    <th>Item Code</th>
                    <th>Image</th>
                    <th>Size</th>
                    <th>Unit</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Recieved/Issued</th>
                    <th>Branch</th>                    
                    <th>Remark</th>
                    <th>Check</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($histories as $history)                                  
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{ get_outlet_name($history->outlet_id) }}</td> 
                        <td>{{ $history->date }}</td>
                        <td>{{ $history->item_code }}</td>
                        <td> <img src = "{{asset('storage/' . $history->image)}}" alt="images"/></td>                        
                        <td>{{ isset($size_variants[$history->size_variant_value]) ? $size_variants[$history->size_variant_value] : ''}}
                        <td>{{ isset($units[$history->unit_id]) ? $units[$history->unit_id] : ''}}
                        <td>{{ isset($categories[$history->category_id]) ? $categories[$history->category_id] : ''}}
                        <td>{{ $history->quantity }}</td>
                        <td>{{ $types[$history->type] }}</td>
                        <td>{{ $history->branch }}</td>
                        <td>{{ $history->remark }}</td>              
                        <td class="text-center"><input class="form-check-input mt-0 outletlevelhistory-check" type="checkbox" value="{{ $history->id }}" aria-label="Checkbox for following text input" {{ ($history->is_check == 1) ? 'checked' : '' }} /></td>              
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
