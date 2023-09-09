@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Edit Country</h4>
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
        {!! Form::model($country, [
            'method' => 'PATCH',
            'route' => ['countries.update', $country->id],
            'enctype' => 'multipart/form-data',
        ]) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 col-sm-6">
                {{ Form::label('name', 'Name', ['class' => 'form-label' . ($errors->has('name') ? ' text-danger' : '')]) }}
                {{ Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name', 'placeholder' => 'Country Name']) }}
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="">
            <a href="{{ route('countries.index')}}" class="btn btn-red">Back</a>
            <button type="submit" class="btn btn-blue ms-2">Update</button>
        </div>
        {!! Form::close() !!}

    </div>
@endsection
