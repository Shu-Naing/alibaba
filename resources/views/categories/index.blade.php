@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">List Category</h4>
            <div>
            </div>
        </div>
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('categories.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                  <th>Category</th>
                  <th>Category Code</th>
                  <th>Descriptions</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cate)
                    <tr>
                        <td>{{ $cate->category_name }}</td>
                        <td>{{ $cate->category_code }}</td>
                        <td>{{ $cate->description }}</td>
                        <td>
                            <a class="text-decoration-underline px-3" href="{{ route('categories.edit',$cate->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['categories.destroy', $cate->id],'style'=>'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'text-danger text-decoration-underline btn btn-link p-0', 'style' => 'font-family: Arial, sans-serif; font-size: 14px;']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
