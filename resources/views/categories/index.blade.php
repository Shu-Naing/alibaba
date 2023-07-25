@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Category</h4>
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
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('categories.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cate)
                    <tr>
                        <td>{{ $cate->category_name }}</td>
                        <td>
                            <a class="text-decoration-underline px-3" href="{{ route('categories.edit', $cate->id) }}"><i
                                    class="fa-solid fa-pen-to-square"></i> Edit</a>

                            <a class="text-danger btn btn-link p-0" href="" data-bs-toggle='modal' 
                            data-bs-target='#catdelete' style='font-family: Arial, sans-serif; font-size: 14px;'> delete</a>
                        </td>
                    </tr>

                    <x-delete-modal id="catdelete" deletedataid="{{$cate->id}}" route="categories.destroy"></x-delete-modal>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
