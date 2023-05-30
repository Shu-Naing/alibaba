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
            </div>
        </div>
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('units.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Allow Decimal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($units as $unit)
                    <tr>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->short_name }}</td>
                        <td>{{ $unit->allow_decimal }}</td>
                        <td>
                          <a class="px-3" href="{{ route('units.edit',$unit->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['units.destroy', $unit->id], 'style' => 'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'border-0', 'style' => 'font-family: Arial, sans-serif; font-size: 14px;']) !!}
                            {!! Form::close() !!}
                          </i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
