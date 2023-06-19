@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create User</h4>
            <div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3 border">
                    {!! Form::open(['route' => 'users.store', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <strong>User Name:</strong>
                                {!! Form::text('username', null, ['placeholder' => 'User Name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 my-3">
                            <div class="form-group">
                                <strong>Email:</strong>
                                {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 my-3">
                            <div class="form-group">
                                <strong>Outlets:</strong>
                                {!! Form::select('outlet_id', $outlets->pluck('name', 'id'), null, [
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
                                {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'multiple']) !!}
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
