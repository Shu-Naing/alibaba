@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">List Selling Price Group</h4>
            <div>
            </div>
        </div>
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('sellingprice.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr class="col">
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sellingprice as $sell)
                    <tr>
                        <td>{{ $sell->name }}</td>
                        <td>{{ $sell->descriptions }}</td>
                        <td class="d-flex gap-5">
                            <a class="text-decoration-underline" href="{{ route('sellingprice.edit',$sell->id) }}">Edit</a>
                            @if ($sell->status == 1)
                                <a href="{{ route('sellingprice.deactivate', ['id' => $sell->id]) }}" class="text-decoration-underline text-danger">Deactivate</a>
                            @else
                                <a href="{{ route('sellingprice.activate', ['id' => $sell->id]) }}" class="text-decoration-underline text-success">Activate</a>
                            @endif
                            {!! Form::open(['method' => 'DELETE', 'route' => ['sellingprice.destroy', $sell->id]]) !!}
                                {!! Form::submit('Delete', ['class' => 'text-danger text-decoration-underline btn btn-link p-0', 'style' => 'font-family: Arial, sans-serif; font-size: 14px;']) !!}
                            {!! Form::close() !!}                              
                                
                            
                          </i>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <style>
        .btng {
            background-color: red;
            border: none;
            color: white;
            padding: 5px 5px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 20px;
        }

        .green {
            background-color: #199319;
        }

        .red {
            background-color: red;
        }
    </style>
@endsection
