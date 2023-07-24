@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection
@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Size Variants</h4>
            <div>
                @include('breadcrumbs')
            </div>
        </div>
       
        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="d-flex mb-3 justify-content-end">
            <a class="btn btn-blue" href="{{ route('size-variant.create') }}">Add +</a>
        </div>
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Value</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            @php $no = 1 ; @endphp
            @foreach ($data as $key => $size_variant)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $size_variant->value }}</td>
                    <td>
                       
                        <a class="px-3" href="{{ route('size-variant.edit', $size_variant->id) }}"><i
                                class="fa-solid fa-pen-to-square"></i> Edit</a>

                        <a class="text-danger text-decoration-underline btn btn-link p-0" href="" data-bs-toggle='modal' 
                            data-bs-target='#svariantsdelete' style='font-family: Arial, sans-serif; font-size: 14px;'> delete</a>

                            <!-- {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['size-variant.destroy', $size_variant->id],
                                'style' => 'display:inline',
                            ]) !!}
                            {!! Form::submit('Delete', [
                                'class' => 'text-danger text-decoration-underline btn btn-link p-0',
                                'style' => 'font-family: Arial, sans-serif; font-size: 14px;',
                            ]) !!}
                            {!! Form::close() !!} -->
                    </td>
                </tr>

                <x-delete-modal id="svariantsdelete" deletedataid="{{$size_variant->id}}" route="size-variant.destroy"></x-delete-modal>
            @endforeach
        </table>
    </div>
    </div>
@endsection
