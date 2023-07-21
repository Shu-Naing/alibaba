@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Edit brand</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
        
        @if (Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif
        {!! Form::model($brands, [
            'route' => ['brands.update', $brands->id],
            'method' => 'put',
            'enctype' => 'multipart/form-data',
            'class' => 'px-3',
        ]) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 col-sm-6">
                {{ Form::label('brand_name', 'Brand Name', ['class' => 'form-label' . ($errors->has('brand_name') ? ' text-danger' : '')]) }}
                {{ Form::text('brand_name', null, ['class' => 'form-control' . ($errors->has('brand_name') ? ' is-invalid' : ''), 'id' => 'brand_name', 'placeholder' => 'Brand Name']) }}
                @error('brand_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
          
        </div>
        <div class="">
            <a href="{{ URL::current() }}" class="btn btn-red">Cancel</a>
            <button type="submit" class="btn btn-blue ms-2">Update</button>
        </div>
        {!! Form::close() !!}

    </div>
@endsection
