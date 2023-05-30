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
        <form class="p-4 rounded border shadow-sm" action="{{ route('machine.store') }}" method="post">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="outlet" class="form-label">Outlet</label>
                    <select class="form-select" name="outlet" id="outlet" aria-label="Default select example">
                        <option selected></option>
                        @if($outlets)
                            @foreach ($outlets as $outlet) 
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Machine Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="text-center">
                <a class="btn btn-red" href="{{ url()->previous() }}">Cancel</a>
                <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
                <button type="submit" class="btn btn-blue ms-2">Save</button>
            </div>
        </form>
    </div>
@endsection