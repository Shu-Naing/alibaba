@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Purchased Price History</h4>
            <div>
            </div>
        </div>

        <div class="my-3">
            <a href="{{ route('purchased-price-history.export') }}" class="btn btn-red me-2">Export to Excel</a>
        </div>
       
        <table id="table_id">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Purchased Price</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($purchased_price_histories as $purchased_price_history)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $purchased_price_history->variation->item_code }}</td>
                    <td>{{ $purchased_price_history->purchased_price }}</td>
                    <td>{{ $purchased_price_history->quantity }}</td>
                    <td>{{ date('d-m-y',strtotime($purchased_price_history->created_at)) }}</td>
               </tr>
                @endforeach 
            </tbody>
        </table>
    </div>
@endsection
