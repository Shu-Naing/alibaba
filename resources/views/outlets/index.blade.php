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
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outlets as $outlet)
                    <tr>
                        <td>{{ $outlet->id }}</td>
                        <td>{{ $outlet->name }}</td>
                        <td>{{ $outlet->city }}</td>
                        <td>{{ $outlet->state }}</td>
                        <td>{{ $outlet->category_name }}</td>
                        <td>hello</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
