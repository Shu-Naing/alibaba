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


<form class="px-3" action="{{ route('brands.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="brand" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('brand_name') is-invalid @enderror" name="brand_name" id="brand_name" aria-describedby="emailHelp">
                    @error('name')
                        <span class="text-danger ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-12">
                    <label for="name" class="form-label">Short Description *</label>
                    <textarea class="form-control @error('note') is-invalid @enderror" name="note" id="note" style="height: 200px;"></textarea>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="text-center">
                <a class="btn btn-red" href="{{ URL::previous() }}">Cancel</a>
                <button type="submit" class="btn btn-blue ms-2">Save</button>
            </div>
</form>

@endsection