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
                @include('breadcrumbs')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card p-3 border">
                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}

                    <div class="row g-2">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            {{ Form::label('name', 'Name:', ['class' => 'form-label' . ($errors->has('name') ? ' text-danger' : '')]) }}
                            {{ Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'id' => 'name', 'placeholder' => 'Name']) }}
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            {{ Form::label('username', 'User Name:', ['class' => 'form-label' . ($errors->has('username') ? ' text-danger' : '')]) }}
                            {{ Form::text('username', null, ['class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'id' => 'username', 'placeholder' => 'User Name']) }}
                            @error('username')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 my-3">
                            {{ Form::label('email', 'Email:', ['class' => 'form-label' . ($errors->has('email') ? ' text-danger' : '')]) }}
                            {{ Form::text('email', null, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'id' => 'email', 'placeholder' => 'Email']) }}
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 my-3">
                            {{ Form::label('outlet_id', 'Outlets:', ['class' => 'form-label' . ($errors->has('outlet_id') ? ' text-danger' : '')]) }}
                            {{ Form::select('outlet_id', ['' => 'Choose outlets'] + $outlets->pluck('name', 'id')->toArray(), old('outlet_id', $user->outlet->id), ['class' => 'form-control' . ($errors->has('outlet_id') ? ' is-invalid' : '')]) }}
                            @error('outlet_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            {{ Form::label('password', 'Password:', ['class' => 'form-label' . ($errors->has('password') ? ' text-danger' : '')]) }}
                            {{ Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''), 'id' => 'password', 'placeholder' => 'Password']) }}
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            {{ Form::label('confirm-password', 'Confirm Password:', ['class' => 'form-label' . ($errors->has('confirm-password') ? ' text-danger' : '')]) }}
                            {{ Form::password('confirm-password', ['class' => 'form-control' . ($errors->has('confirm-password') ? ' is-invalid' : ''), 'id' => 'confirm-password', 'placeholder' => 'Confirm Password']) }}
                            @error('confirm-password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            {{ Form::label('roles', 'Role:', ['class' => 'form-label' . ($errors->has('roles') ? ' text-danger' : '')]) }}
                            {{ Form::select('roles[]', $roles, old('roles', $userRole), ['class' => 'form-control' . ($errors->has('roles') ? ' is-invalid' : ''), 'multiple']) }}
                            @error('roles')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
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
