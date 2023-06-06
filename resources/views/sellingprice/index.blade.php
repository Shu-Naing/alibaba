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
            <a class="btn btn-blue" href="{{ route('sellingprice.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
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
                        <td>
                          <a class="px-3" href="{{ route('sellingprice.edit',$sell->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <!-- Assuming $sellingprice is the object representing the sellingprice in your view -->
                              
                                @if ($sell->status == 1)
                                    <a href="{{ route('sellingprice.deactivate', ['id' => $sell->id]) }}" class="btng red">Deactivate</a>
                                @else
                                    <a href="{{ route('sellingprice.activate', ['id' => $sell->id]) }}" class="btng green">Activate</a>
                                @endif
                            {!! Form::open(['method' => 'DELETE', 'route' => ['sellingprice.destroy', $sell->id], 'style' => 'display:inline']) !!}
                                {!! Form::submit('Delete', ['class' => 'border-0', 'style' => 'font-family: Arial, sans-serif; font-size: 14px;']) !!}
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
            font-size: 15px;
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
