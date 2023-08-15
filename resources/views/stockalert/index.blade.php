@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Stock Alert</h4>
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
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($stockalerts as $stockalert)
                    <tr>
                        <td class="w-25">{{++$i}}</td>
                        <td>{{ $stockalert->message }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
