@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content pb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Distribute</h4>
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
            $from_date = session()->get(DISTRIBUTE_FROMDATE_FILTER);
            $to_date = session()->get(DISTRIBUTE_TODATE_FILTER);
            $from_outlet = session()->get(DISTRIBUT_FROMOUTLET_FILTER);
            $to_outlet = session()->get(DISTRIBUT_TOOUTLET_FILTER);
            $vouncher_no = session()->get(DISTRIBUT_VOUNCHERNO_FILTER);
            $status = session()->get(DISTRIBUT_STATUS_FILTER);
        @endphp

        {!! Form::open([
            'route' => 'search-list-distribute',
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
                <div class="col-md-2">
                    {!! Form::label('fromOutlet', 'From Outlet', ['class' => 'form-label']) !!}
                    {!! Form::select('fromOutlet',$outlets, $from_outlet, ['placeholder'=>'Choose..','class' => 'form-control', 'id' => 'fromOutlet']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('toOutlet', 'To Outlet', ['class' => 'form-label']) !!}
                    {!! Form::select('toOutlet', $outlets, $to_outlet, ['placeholder'=>'Choose..','class' => 'form-control', 'id' => 'toOutlet']) !!}
                </div>
                <div class="col-md-2">
                    {!! Form::label('vouncher_no', 'Vouncher No', ['class' => 'form-label']) !!}
                    {{ Form::text('vouncher_no', $vouncher_no, ['placeholder'=>'Enter here','class' => 'form-control']) }}
                </div>
                <div class="col-md-2">
                    {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                    {!! Form::select('status',$ds_status, $status, ['placeholder'=>'Choose..','class' => 'form-control', 'id' => 'fromOutlet']) !!}
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-blue ms-2">Search</button>
                    <a href="{{route('distribute-search-reset')}}" class="btn btn-blue ms-2">Reset</a>
                    <!-- <a href="{{ route('distribute-detail-export') }}" class="btn btn-blue ms-2">Export to Excel</a> -->
                </div>
            </div>
        {!! Form::close() !!}

        <table class="table table-bordered text-center shadow rounded" id="table_id">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Reference No</th>
                    <th scope="col">From Outlet</th>
                    <th scope="col">To Outlet</th>
                    <th scope="col">Vouncher No</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distributes as $distribute)
                    <tr>
                        <td>{{$distribute->date}}</td>
                        <td>{{$distribute->reference_No}}</td>
                        <td>{{$outlets[$distribute->from_outlet]}}</td>
                        <td>{{$outlets[$distribute->to_outlet]}}</td>
                        <td>{{ $distribute->vouncher_no }}</td>
                        <td class="
                            @if($distribute->status == '2') text-success 
                            @elseif ($distribute->status == '1') text-warning 
                            @elseif ($distribute->status == '0') text-danger 
                            @endif
                        ">
                            {{ $ds_status[$distribute->status] }}</td>
                        <td>
                            <a href="{{ route('distribute.show',$distribute->id) }}" class="mx-2">View</a>
                            {{-- <a href="{{ route('distribute.edit',[$distribute->id, $distribute->from_outlet]) }}" class="mx-2">Edit</a> --}}
                        </td>
                    </tr>
                @endforeach                    
            </tbody>
        </table>
    </div>
@endsection
