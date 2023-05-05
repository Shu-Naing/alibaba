@extends('layouts.navbar')
@section('cardtitle')
    <h4>Category Update</h4>
@endsection

@section('cardbody')

@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif


{!! Form::model($cate, [ 'method' => 'PATCH', 'route' => ['categories.update', $cate->id], 'enctype' => 'multipart/form-data']) !!}
<div class="row">
<div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Category Name:</strong>
            {!! Form::text('category_name', null, array('placeholder' => 'Category Name','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Category Code:</strong>
            {!! Form::text('category_code', null, array('placeholder' => 'Category Code','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
        <div class="form-group">
            <strong>Description:</strong>
            {!! Form::textarea('description', null, array('placeholder' => 'Description', 'class' => 'form-control', 'size' => '50x5')) !!}
        </div>
    </div> 
    
    
    <div class="col-xs-12 col-sm-12 col-md-12 py-4">
        <a class="btn btn-primary" href="{{ route('categories.index') }}"> Back</a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>
{!! Form::close() !!}

@endsection