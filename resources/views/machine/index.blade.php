@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">List Machines</h4>
            <div>
            </div>
        </div>


        <table id="table_id">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Machine Name</th>
                    <th>Outlet ID</th>
                    <th>Outlet Name</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($machines as $machine)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $machine->outlet->outlet_id }}</td>
                        <td>{{ $machine->outlet->name }}</td>
                        <td>
                            @if ($machine->outlet->city == 1)
                                Yangon
                            @elseif($machine->outlet->city == 2)
                                Mandalay
                            @elseif($machine->outlet->city == 3)
                                Naypyidaw
                            @endif
                        </td>
                        <td>
                            @if ($machine->outlet->state == 1)
                                Hledan
                            @elseif($machine->outlet->state == 2)
                                Myaynigone
                            @elseif($machine->outlet->state == 3)
                                Ahlone
                            @endif
                        </td>
                        <td>
                            @if ($machine->outlet->country == 1)
                                Myanmar
                            @elseif($machine->outlet->country == 2)
                                China
                            @elseif($machine->outlet->country == 3)
                                Korea
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
