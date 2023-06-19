@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Edit Selling Price Group</h4>
            <div>
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
        {!! Form::model($sellingprice, [
            'route' => ['sellingprice.update', $sellingprice->id],
            'method' => 'put',
            'enctype' => 'multipart/form-data',
            'class' => 'px-3',
        ]) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-12">
                {!! Form::label('name', 'Name *', ['class' => 'form-label']) !!}
                {!! Form::text('name', $sellingprice->name, ['class' => 'form-control mb-3', 'id' => 'name']) !!}
            </div>
            <div class="col-md-12">
                {!! Form::label('descriptions', 'descriptions *', ['class' => 'form-label']) !!}
                {!! Form::textarea('descriptions', $sellingprice->descriptions, [
                    'class' => 'form-control mb-3',
                    'id' => 'descriptions',
                    'cols' => '40',
                    'rows' => '4',
                ]) !!}
            </div>
        </div>
        <div class="text-center">
            <a href="{{ URL::previous() }}" class="btn btn-red">Cancel</a>
            <button type="submit" class="btn btn-blue ms-2">Update</button>
        </div>
        {!! Form::close() !!}

    </div>
@endsection
