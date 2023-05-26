@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Create Outlet</h4>
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
        <form class="px-3" action="{{ route('outlets.store') }}" method="post">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="outletId" class="form-label">Outlet ID *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="outletId" id="outletId" aria-describedby="emailHelp">
                    @error('name')
                        <span class="text-danger ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" name="city" id="city" aria-label="Default select example">
                        <option selected></option>
                        <option value="1">Yangon</option>
                        <option value="2">Mandalay</option>
                        <option value="3">Naypyidaw</option>
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" name="state" id="state" aria-label="Default select example">
                        <option selected></option>
                        <option value="1">Insein</option>
                        <option value="2">Shwepyithar</option>
                        <option value="3">hlaing</option>
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <select class="form-select" name="country" id="country" aria-label="Default select example">
                    <option selected></option>
                    <option value="1">Myanmar</option>
                    <option value="2">USA</option>
                    <option value="3">Japan</option>
                </select>
                <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
            </div>
            <div class="text-center">
                <a class="btn btn-red" href="{{ URL::previous() }}">Cancel</a>
                <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
                <button type="submit" class="btn btn-blue ms-2">Save</button>
            </div>
        </form>
    </div>
@endsection
