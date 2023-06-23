@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Distribute Product</h4>
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

            <table class="table table-bordered text-center shadow rounded">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Ref:</th>
                        <th scope="col">From Outlet</th>
                        <th scope="col">To Machine</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outletDistributes as $distribute)
                        <tr>
                            <td>{{$distribute->date}}</td>
                            <td>{{$distribute->reference_No}}</td>
                            <td>{{$outlets[$distribute->from_outlet]}}</td>
                            <td>{{$machines[$distribute->to_machine]}}</td>
                            <td><a href="{{ route('outletdistribute.show',$distribute->id) }}">View</a></td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
@endsection