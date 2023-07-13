@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create Category</h4>
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
        {!! Form::open(['route' => 'categories.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 col-sm-6">
                <label for="category_name" class="form-label">Category Name *</label>
                <input type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name"
                    id="category_name" aria-describedby="emailHelp">
                @error('category_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6 col-sm-6">
                <label for="category_code" class="form-label">Category Code *</label>
                <input type="text" class="form-control @error('category_code') is-invalid @enderror" name="category_code"
                    id="category_code" aria-describedby="emailHelp">
                @error('category_code')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                    style="height: 200px;"></textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="text-center">
            <a href="{{ URL::previous() }}" class="btn btn-red">Cancel</a>
            <button type="submit" class="btn btn-blue ms-2">Save</button>
        </div>
        {!! Form::close() !!}

    </div>
@endsection
