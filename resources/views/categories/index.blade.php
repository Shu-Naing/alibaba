@extends('layouts.navbar')
@section('cardtitle')
  <h4>Categories</h4>
@endsection

@section('cardbody')
<x-create-btn label="Create New Categories" route="categories"/>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif


<table class="table table-bordered">
 <tr>
   <th>Category</th>
   <th>Category Code</th>
   <th>Descriptions</th>
   <!-- <th width="280px">Action</th> -->
   <th>Action</th>
 </tr>
 @foreach ($categories as $key => $cate)
  <tr>
    <td>{{ $cate->category_name }}</td>
    <td>{{ $cate->category_code }}</td>
    <td>{{ $cate->descriptions }}</td>
  </tr>
 @endforeach
</table>



{!! $categories->render() !!}

@endsection