@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">List Product</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('outlets.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>data-1a</td>
                    <td>data-1b</td>
                    <td>data-1c</td>
                    <td>data-1c</td>
                    <td>data-1c</td>
                </tr>
                <tr>
                    <td>data-2a</td>
                    <td>data-2b</td>
                    <td>data-2c</td>
                    <td>data-2c</td>
                    <td>data-2c</td>
                </tr>
                <tr>
                    <td>data-3a</td>
                    <td>data-3b</td>
                    <td>data-3c</td>
                    <td>data-3c</td>
                    <td>data-3c</td>
                </tr>
                <tr>
                    <td>data-4a</td>
                    <td>data-4b</td>
                    <td>data-4c</td>
                    <td>data-4c</td>
                    <td>data-4c</td>
                </tr>  
            </tbody>
        </table>
    </div>
@endsection
