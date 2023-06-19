@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection
@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Edit User</h4>
            <div>
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


        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3 border">
                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {!! Form::text('name', old('name', $user->name), ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>User Name:</strong>
                                {!! Form::text('username', old('username', $user->username), [
                                    'placeholder' => 'User Name',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 my-3">
                            <div class="form-group">
                                <strong>Email:</strong>
                                {!! Form::text('email', old('email', $user->email), ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 my-3">
                            <div class="form-group">
                                <strong>Outlets:</strong>
                                {!! Form::select('outlet_id', $outlets->pluck('name', 'id'), old('outlet_id', $user->outlet->id), [
                                    'placeholder' => 'Choose outlets',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Password:</strong>
                                {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Confirm Password:</strong>
                                {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}
                            </div>
                        </div>


                        <div class="col-xs-6 col-sm-6 col-md-6 mt-3">
                            <div class="form-group">
                                <strong>Role:</strong>
                                {!! Form::select('roles[]', $roles, old('roles', $userRole), ['class' => 'form-control', 'multiple']) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 py-4">
                            <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>


@endsection
