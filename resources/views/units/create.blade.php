@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create unit</h4>
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
            
        </form>


        {!! Form::open([
            'route' => 'units.store',
            'method' => 'post',
            'class' => 'px-3 mb-5',
            'id' => 'distribute',
            'enctype' => 'multipart/form-data'
        ]) !!}
        @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-6">
                    {!! Form::label('unit', 'Name *', ['class' => 'form-label']) !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::label('unit', 'Short Name *', ['class' => 'form-label']) !!}
                    {!! Form::text('short_name', null, ['class' => 'form-control', 'id' => 'short_name']) !!}
                </div>
                <div class="col">
                    {{ Form::checkbox('allow_decimal') }} 
                    {!! Form::label('allow_decimal', 'Decimal Permission', ['class' => 'form-label']) !!}
                </div>
            </div>

            <div class="text-center">
                <a class="btn btn-red" href="{{ URL::previous() }}">Cancel</a>
                <button type="submit" class="btn btn-blue ms-2">Save</button>
            </div>
        {!! Form::close() !!}

        
    </div>
@endsection
