@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">update brand</h4>
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
{!! Form::model($sellingprice, ['route' => ['sellingprice.update', $sellingprice->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'px-3']) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="name" class="form-label">Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" aria-describedby="emailHelp" value="{{ old('name', $sellingprice->name) }}">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12">
                <label for="descriptions" class="form-label">Short Description *</label>
                <textarea class="form-control @error('descriptions') is-invalid @enderror" name="descriptions" id="descriptions" style="height: 200px;">{{ old('descriptions', $sellingprice->descriptions) }}</textarea>
                @error('descriptions')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="text-center">
            <a href="{{ URL::previous() }}" class="btn btn-red">Cancel</a>
            <button type="submit" class="btn btn-blue ms-2">Update</button>
        </div>
{!! Form::close() !!}

    </div>
@endsection
