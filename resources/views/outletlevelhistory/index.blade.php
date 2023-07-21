@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Outlet Stock History</h4>
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

        <table id="table_id">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Machine</th>
                    <th>Date</th>
                    <th>Item Code</th>
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
                        <td>{{ $outlets[$history->outlet_id] }}</td> 
                        <td>{{ $history->date }}</td>
                        <td>{{ $history->item_code }}</td>
                        <td>{{ $history->quantity }}</td>
                        <td>{{ $types[$history->type] }}</td>
                        <td>{{ $outlets[$history->branch] }}</td>
                        <td>{{ $history->remark }}</td>              
                        <td class="text-center"><input class="form-check-input mt-0 outletlevelhistory-check" type="checkbox" value="{{ $history->id }}" aria-label="Checkbox for following text input" {{ ($history->is_check == 1) ? 'checked' : '' }} /></td>              
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
