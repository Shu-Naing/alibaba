@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">List Product</h4>
            <div>
                @include('breadcrumbs')
            </div>
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
        <form class="p-4 rounded border shadow-sm mb-5" action="{{ route('machine.store') }}" method="post">
            @csrf
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    <label for="date" class="form-label">Date</label>
                    <input type="text" class="form-control @error('date') is-invalid @enderror" name="date" id="date">
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="reference" class="form-label">Reference No.</label>
                    <input type="text" class="form-control @error('reference') is-invalid @enderror" reference="reference" id="reference">
                    @error('reference')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status" aria-label="Default select example">
                        <option selected></option>
                        <option value="1">Pending</option>
                        <option value="2">approve</option>

                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
                 <div class="col-md-4">
                    <label for="fromOutlet" class="form-label">From (Outlet)</label>
                    <select class="form-select" name="fromOutlet" id="fromOutlet" aria-label="Default select example">
                        <option selected></option>
                        @if($outlets)
                            @foreach ($outlets as $outlet) 
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
                 <div class="col-md-4">
                    <label for="toOutlet" class="form-label">To (Outlet)</label>
                    <select class="form-select" name="toOutlet" id="toOutlet" aria-label="Default select example">
                        <option selected></option>
                        @if($outlets)
                            @foreach ($outlets as $outlet) 
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
            </div>
        </form>

        <h5 class="fw-bold mb-4">Add Products</h5>
        <div class="input-group rounded w-25 mb-3">
            <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" />
            <span class="input-group-text border-0" id="search-addon">
                <i class="bi bi-search"></i>
            </span>
        </div>

        <div>
            <table class="table table-bordered text-center shadow rounded">
                <thead>
                    <tr>
                    <th scope="col" style="width: 30%;">Product Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Purchased Price</th>
                    <th scope="col">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: left;">
                            POKEMON KORORIN FRIENDS PLUSH - PLUSLE . MINUN . TURTWIG . RIOLU - (Size:M-2) BP17923P-1
                        </td>
                        <td> 6Pcs + -</td>
                        <td>9,708</td>
                        <td>58,248</td>
                        <td>Delete</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center">
            <a class="btn btn-red" href="{{ url()->previous() }}">Cancel</a>
            <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
            <button type="submit" class="btn btn-blue ms-2">Save</button>
        </div>
    </div>
@endsection
