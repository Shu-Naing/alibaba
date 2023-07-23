@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create Size Variant</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
       
        @if (Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif
        {!! Form::open(['route' => 'size-variant.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 col-sm-6">
                {{ Form::label('value', 'Value', ['class' => 'form-label' . ($errors->has('value') ? ' text-danger' : '')]) }}
                {{ Form::text('value', null, ['class' => 'form-control' . ($errors->has('value') ? ' is-invalid' : ''), 'id' => 'value', 'placeholder' => 'Value']) }}
                @error('value')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="">
            <a href="{{ URL::current() }}" class="btn btn-red">Cancel</a>
            <button type="submit" class="btn btn-blue ms-2">Save</button>
        </div>
        {!! Form::close() !!}

    </div>
@endsection
