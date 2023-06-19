@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Roles</h4>
            <div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('roles.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <a class="text-decoration-underline px-3" href="{{ route('roles.edit', $role->id) }}"><i
                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                            {!! Form::submit('Delete', [
                                'class' => 'text-danger text-decoration-underline btn btn-link p-0',
                                'style' => 'font-family: Arial, sans-serif; font-size: 14px;',
                            ]) !!}
                            {!! Form::close() !!}
                            </i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
