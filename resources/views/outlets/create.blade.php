@extends('layouts.app')
@section('cardtitle')
    <i class="bi bi-person-fill"></i>
    <span class="loginUser">Welcome, <?php $userName = Auth::user();
    echo $userName->username; ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">
            <h4 class="fw-bolder mb-3">Create Outlet</h4>
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
        @if (Session::has('error'))
            <div>
                {{ Session::get('error') }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::open(['route' => 'outlets.store', 'method' => 'POST', 'class' => 'px-3']) !!}
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                {!! Form::label('outletId', 'Outlet ID *', ['class' => 'form-label']) !!}
                {!! Form::text('outlet_id', null, ['class' => 'form-control', 'id' => 'outletId']) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('name', 'Name *', ['class' => 'form-label']) !!}
                <!-- <label for="name" class="form-label">Name *</label> -->
                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                <!-- <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"> -->
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                {!! Form::label('city', 'City', ['class' => 'form-label']) !!}
                {!! Form::select('city', $cities, null, [
                    'placeholder' => 'Choose',
                    'class' => 'form-control',
                    'id' => 'city-select',
                    'aria-label' => 'Default select example',
                ]) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('state', 'State', ['class' => 'form-label']) !!}
                {!! Form::select('state', $states, null, [
                    'placeholder' => 'Choose',
                    'class' => 'form-control',
                    'id' => 'state-select',
                    'aria-label' => 'Default select example',
                ]) !!}
            </div>
        </div>
        <div class="text-center">
            <a class="btn btn-red" href="{{ route('outlets.index') }}">Cancel</a>
            <button type="submit" class="btn btn-blue ms-2">Save</button>
        </div>
        {!! Form::close() !!}
    </div>

    <!-- <script>
        // Fetch the JSON data file
        fetch('/dummy_data.json')
            .then(response => response.json())
            .then(data => {
                const countrySelect = document.getElementById('country-select');
                const citySelect = document.getElementById('city-select');
                const stateSelect = document.getElementById('state-select');

                // Populate the country select box
                data.countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.id;
                    option.textContent = country.name;
                    countrySelect.appendChild(option);
                });

                // Update city select box based on the selected country
                countrySelect.addEventListener('change', function() {
                    const selectedCountryId = this.value;
                    const filteredCities = data.cities.filter(city => city.country_id == selectedCountryId);

                    // Clear and populate the city select box
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    filteredCities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });

                    // Clear the state select box
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                });

                // Update state select box based on the selected city
                citySelect.addEventListener('change', function() {
                    const selectedCityId = this.value;
                    const filteredStates = data.states.filter(state => state.city_id == selectedCityId);

                    // Clear and populate the state select box
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                    filteredStates.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.id;
                        option.textContent = state.name;
                        stateSelect.appendChild(option);
                    });
                });
            });
    </script> -->
@endsection
