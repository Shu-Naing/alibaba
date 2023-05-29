@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Create brand</h4>
            <div>
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
        <form class="px-3" action="{{ route('brands.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="brand" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('brand_name') is-invalid @enderror" name="brand_name" id="brand_name" aria-describedby="emailHelp">
                    @error('name')
                        <span class="text-danger ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="name" class="form-label">Short Description *</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" name="note" id="note" style="height: 200px;"></textarea>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="text-center">
                <a class="btn btn-red" href="{{ URL::previous() }}">Cancel</a>
                <button type="submit" class="btn btn-blue ms-2">Save</button>
            </div>
        </form>
    </div>
@endsection
