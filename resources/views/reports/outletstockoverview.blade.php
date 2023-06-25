@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Outlet Stock Overview Report</h4>
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

        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Machine Name</th>
                    <th>Item Code</th>
                    <th>Opening Qty</th>
                    <th>Received Qty</th>
                    <th>Issued Qty</th>
                    <th>Balance Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($outletstockoverviews as $outletstockoverview)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $outletstockoverview->name }}</td>
                        <td>{{ $outletstockoverview->item_code }}</td>
                        <td>{{ $outletstockoverview->opening_qty }}</td>
                        <td>{{ $outletstockoverview->receive_qty }}</td>
                        <td>{{ $outletstockoverview->issued_qty }}</td>
                        <td>{{ $outletstockoverview->opening_qty + $outletstockoverview->receive_qty - $outletstockoverview->issued_qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="" style="margin-bottom: 200px;"></div>
    </div>
@endsection
