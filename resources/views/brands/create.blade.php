@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create brand</h4>
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
        {!! Form::open([
            'route' => 'brands.store',
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'class' => 'px-3',
        ]) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-4 col-sm-4">
                {!! Form::label('brand_name', 'Name *', ['class' => 'form-label']) !!}
                {!! Form::text('brand_name', null, ['class' => 'form-control mb-3', 'id' => 'brand_name']) !!}
            </div>
            <div class="col-md-12 col-sm-12">
                {!! Form::label('note', 'Short Description *', ['class' => 'form-label']) !!}
                {!! Form::textarea('note', null, ['class' => 'form-control', 'id' => 'note', 'cols' => '40', 'rows' => '4']) !!}
            </div>
        </div>
        <div class="text-center">
            <a href="{{ URL::previous() }}" class="btn btn-red">Cancel</a>
            <button type="submit" class="btn btn-blue ms-2">Save</button>
        </div>
        {!! Form::close() !!}

    </div>
@endsection
