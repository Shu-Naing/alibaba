@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Purchase</h4>
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
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('purchase.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>GRN No</th>
                    <th>Received Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $purchase->grn_no }}</td>
                        <td>{{ $purchase->received_date }}</td>
                        <td><a href="{{ route('purchase.show',$purchase->grn_no) }}" class="mx-2">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
