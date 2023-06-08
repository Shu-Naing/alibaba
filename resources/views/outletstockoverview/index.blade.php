@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Create Outlet</h4>
            <div></div>
        </div>
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif
        <form class="p-4 rounded border shadow-sm" action="{{ route('machine.store') }}" method="post">
            @csrf
            <div class="row mb-3 col-6">
                <div class="col-md-6">
                    <label for="outlet" class="form-label">Outlet</label>
                    <select class="form-select" name="outlet" id="outlet" aria-label="Default select example">
                        <option selected></option>
                        @if($outlets)
                            @foreach ($outlets as $outlet) 
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
                <div class="col-md-6">
                    <label for="machine" class="form-label">Location (Store or Machine)</label>
                    <select class="form-select" name="machine" id="machine" aria-label="Default select example">
                        <option selected></option>
                        @if($machines)
                            @foreach ($machines as $machine) 
                                <option value="{{ $machine->id }}">{{ $machine->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </form>
    </div>
@endsection
