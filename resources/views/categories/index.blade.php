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

<x-button-group :buttons="[
    ['label' => 'Export to CSV', 'url' => '/button-1'],
    ['label' => 'Export to Excel', 'url' => '/button-2'],
    ['label' => 'Print', 'url' => '/button-3'],
    ['label' => 'Export to PDF', 'url' => '/button-3'],
]"/>
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
    <td>{{ $cate->description }}</td>
    <td>
      <a class="px-3" href="{{ route('categories.edit',$cate->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
      <i class="fa-regular fa-trash-can">
      {!! Form::open(['method' => 'DELETE','route' => ['categories.destroy', $cate->id],'style'=>'display:inline']) !!}
          {!! Form::submit('Delete', ['class' => 'border-0', 'style' => 'font-family: Arial, sans-serif; font-size: 14px;']) !!}
      {!! Form::close() !!}
      </i>
    </td>
  </tr>
 @endforeach
</table>



{!! $categories->render() !!}

@endsection