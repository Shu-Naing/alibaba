@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Brands</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('brands.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brands as $brand)
                    <tr>
                        <td>{{ $brand->brand_name }}</td>
                        <td>{{ $brand->note }}</td>
                        <td>
                            <a class="text-decoration-underline px-3" href="{{ route('brands.edit', $brand->id) }}"><i
                                    class="fa-solid fa-pen-to-square"></i> Edit</a>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['brands.destroy', $brand->id], 'style' => 'display:inline']) !!}
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
