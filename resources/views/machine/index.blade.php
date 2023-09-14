@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Machines</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>ID</th>                  
                    <th>Machine Name</th>
                    <th>Outlet Name</th>                        
                    <th>Outlet ID</th>                  
                    <th>Country</th>
                    <th>City</th>
                    <th>Township</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($machines as $machine)
                    <tr>
                        <td>{{ $no++ }}</td>  
                        <td>{{ $machine->name }}</td>
                        <td>{{ $machine->outlet->name }}</td>                      
                        <td>{{ $machine->outlet->outlet_id }}</td>
                        <td>{{ isset($countries[$machine->country]) ? $countries[$machine->country] : ''}}
                        <td>{{ isset($cities[$machine->cities]) ? $cities[$machine->cities] : ''}}
                        <td>{{ isset($state[$machine->state]) ? $state[$machine->state] : ''}}                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
