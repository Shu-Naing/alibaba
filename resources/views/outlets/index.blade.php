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
         @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif
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
                    <!-- <th>Category Name</th> -->
                    <th class="w-25">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($outlets as $outlet)
                    <tr>
                        <td>{{ $outlet->id }}</td>
                        <td>{{ $outlet->name }}</td>
                        <td>{{ $outlet->city }}</td>
                        <td>{{ $outlet->state }}</td>
                        <!-- <td>{{ $outlet->category_name }}</td> -->
                        <td class="d-flex gap-5">
                            <a class="text-decoration-underline" href="{{ route('outlets.edit', ['outlet' => $outlet->id] ) }}">Edit</a>
                            <a class="text-decoration-underline" href="">Settings</a>
                            <form action="{{ route('outlets.destroy', $outlet->id) }}" method="POST">
                                @csrf
                                @method('DELETE') <!-- Change this to the desired HTTP method -->
                                <button type="submit" class="text-muted text-decoration-underline btn btn-link p-0">
                                    @if ($outlet->status == 1)
                                        active
                                    @else
                                        deactive
                                    @endif 
                                </button>
                            </form>
                            
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection