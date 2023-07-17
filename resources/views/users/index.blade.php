@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection
@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List User</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
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
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('users.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            @foreach ($data as $key => $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if (!empty($user->getRoleNames()))
                            @foreach ($user->getRoleNames() as $v)
                                <label class="badge bg-warning text-dark">{{ $v }}</label>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <!-- <a href="{{ route('users.show', $user->id) }}"><i class="fa-regular fa-eye"></i> Show</a> -->
                        <a class="px-3" href="{{ route('users.edit', $user->id) }}"><i
                                class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <!--
                            <i class="fa-regular fa-trash-can"></i>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id], 'style' => 'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'border-0']) !!}
                                {!! Form::close() !!} -->
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    </div>
@endsection
