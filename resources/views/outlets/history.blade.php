@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content mb-5">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Outlet Edit</h4>
            <div></div>
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
                    <th>Date</th>
                    <th>From Outlet</th>
                    <th>To Outlet</th>
                    <th>Issue/Recieve</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['issue'] as $fromoutelet)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$fromoutelet->date}}</td>
                        <td>{{$outlets[$fromoutelet->from_outlet]}}</td>
                        <td>{{$outlets[$fromoutelet->to_outlet]}}</td>
                        <td>Issue</td>
                        <td>{{$fromoutelet->quantity}}</td>
                    </tr>
                @endforeach
                @foreach ($data['recieve'] as $tooutelet)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$tooutelet->date}}</td>
                        <td>{{$outlets[$tooutelet->from_outlet]}}</td>
                        <td>{{$outlets[$tooutelet->to_outlet]}}</td>
                        <td>Recieve</td>
                        <td>{{$tooutelet->quantity}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
