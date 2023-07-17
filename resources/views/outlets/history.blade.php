@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Outlet History</h4>
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

        <form action="{{ route('outlethistory.history') }}" method="get">
            @csrf
            <div class="d-flex">
                <select class="form-select w-25" id="outlethistory_select" name="outlet" aria-label="Default select example">
                    @foreach ($outlets as $key => $outlet)
                        <option value="{{ $key }}">{{ $outlet }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-blue ms-2">Select</button>
            </div>
        </form>                
        
        @php
            $i = 0;
        @endphp
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Outlet</th>
                    <th>Date</th>
                    <th>Item Code</th>
                    <th>Quantity</th>
                    <th>Issue/Recieve</th>
                    <th>Branch</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['issue'] as $fromoutelet)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$outlets[$fromoutelet->from_outlet]}}</td>
                        <td>{{$fromoutelet->date}}</td>
                        <td>{{$fromoutelet->item_code}}</td>
                        <td>{{$fromoutelet->quantity}}</td>
                        <td>Issue</td>
                        <td>{{$outlets[$fromoutelet->to_outlet]}}</td>
                        <td>{{$fromoutelet->remark}}</td>
                    </tr>
                @endforeach
                @foreach ($data['recieve'] as $tooutelet)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$outlets[$tooutelet->to_outlet]}}</td>
                        <td>{{$tooutelet->date}}</td>
                        <td>{{$tooutelet->item_code}}</td>
                        <td>{{$tooutelet->quantity}}</td>
                        <td>Recieve</td>
                        <td>{{$outlets[$tooutelet->from_outlet]}}</td>
                        <td>{{$tooutelet->remark}}</td>
                    </tr>
                @endforeach
                @foreach ($data['outletrecieve'] as $recieve)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$outlets[$recieve->from_outlet]}}</td>
                        <td>{{$recieve->date}}</td>
                        <td>{{$recieve->item_code}}</td>
                        <td>{{$recieve->quantity}}</td>
                        <td>{{ (isset($recieve->type)) ? $types[$recieve->type] : 'Issue'}}</td>
                        <td>{{$machines[$recieve->to_machine]}}</td>
                        <td>{{$recieve->remark}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>outletrecieve
    </div>
@endsection
