@extends('layouts.navbar')
@section('cardtitle')
  <h4>Users Management</h4>
@endsection

@section('cardbody')
<x-create-btn label="Create New User" route="users"/>


@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif


<table class="table table-bordered">
<x-button-group :buttons="[
    ['label' => 'Button 1', 'url' => '/button-1'],
    ['label' => 'Button 2', 'url' => '/button-2'],
    ['label' => 'Button 3', 'url' => '/button-3'],
]"/>

 <tr>
   <th>Name</th>
   <th>Email</th>
   <th>Roles</th>
   <th width="280px">Action</th>
 </tr>
 @foreach ($data as $key => $user)
  <tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>
      @if(!empty($user->getRoleNames()))
        @foreach($user->getRoleNames() as $v)
           <label class="badge badge-success bg-warning text-dark">{{ $v }}</label>
        @endforeach
      @endif
    </td>
    <td>
       <a href="{{ route('users.show',$user->id) }}"><i class="fa-regular fa-eye"></i> Show</a>
       <a class="px-3" href="{{ route('users.edit',$user->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
<!--        
       <i class="fa-regular fa-trash-can"></i>
       {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'border-0']) !!}
        {!! Form::close() !!} -->
    </td>
  </tr>
 @endforeach
</table>


{!! $data->render() !!}

@endsection