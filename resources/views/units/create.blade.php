@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Create unit</h4>
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
        <form class="px-3" action="{{ route('units.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="unit" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" aria-describedby="emailHelp">
                    @error('name')
                        <span class="text-danger ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="unit" class="form-label">Short Name *</label>
                    <input type="text" class="form-control @error('short_name') is-invalid @enderror" name="short_name" id="short_name" aria-describedby="emailHelp">
                    @error('name')
                        <span class="text-danger ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="allow_decimal" class="form-label">Decimal Value</label>
                    <input type="number" step="0.01" pattern="\d+(\.\d{1,2})?" class="form-control @error('allow_decimal') is-invalid @enderror" name="allow_decimal" id="allow_decimal" aria-describedby="emailHelp">
                    @error('allow_decimal')
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
