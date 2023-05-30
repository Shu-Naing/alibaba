@extends('layouts.navbar')
@section('cardtitle')
<i class="bi bi-person-fill"></i>
<span class="loginUser">Welcome, <?php $userName = Auth::user(); echo $userName->username ?></span>
@endsection

@section('cardbody')
    <div class="container-fluid main-content">
        <div class="breadcrumbBox rounded mb-4">  
            <h4 class="fw-bolder mb-3">Outlet Edit</h4>
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
        <form class="px-3" action="{{ route('outlets.update', ['outlet' => $outlet->id]) }}" method="post">
            @csrf
            @method('PATCH')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="outletId" class="form-label">Outlet ID *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="outletId" id="outletId" value="{{ old('outlet_id', $outlet->outlet_id ) }}" aria-describedby="outletIdHelp">
                    @error('name')
                        <span class="text-danger ">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('outlet_name', $outlet->name) }}">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" name="city" id="city-select" aria-label="cityHelp">
                        <option></option>
                        <!-- <option value="1">Yangon</option>
                        <option value="2">Mandalay</option>
                        <option value="3">Naypyidaw</option> -->
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" name="state" id="state-select" aria-label="stateHelp">
                        <option></option>
                        <!-- <option value="1">Insein</option>
                        <option value="2">Shwepyithar</option>
                        <option value="3">hlaing</option> -->
                    </select>
                    <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
                </div>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <select class="form-select" name="country" id="country-select" aria-label="countryHelp">
                   <option value="">Select Country</option>
                </select>
                <!-- <input type="text" class="form-control" id="exampleInputPassword1"> -->
            </div>
            <div class="text-center">
                <a class="btn btn-red" href="{{ route('outlets.index') }}">Cancel</a>
                <!-- <button type="submit" class="btn btn-red">Cancel</button> -->
                <button type="submit" class="btn btn-blue ms-2">Update</button>
            </div>
        </form>
    </div>

    <!-- JavaScript code to handle the select box interactions -->
    <script>
        const countrySelect = document.getElementById('country-select');
        const citySelect = document.getElementById('city-select');
        const stateSelect = document.getElementById('state-select');

        // Set the initial selected values based on the old data
        const selectedCountry = '{{ old("country", $outlet->country) }}';
        const selectedCity = '{{ old("city", $outlet->city) }}';
        const selectedState = '{{ old("state", $outlet->state) }}';

        // JSON data for countries, cities, and states
        const countries = @json($dummyData->countries);
        const cities = @json($dummyData->cities);
        const states = @json($dummyData->states);

        // Populate the country select box
        countries.forEach(country => {
            const countryOption = document.createElement('option');
            countryOption.value = country.id;
            countryOption.textContent = country.name;
            console.log(typeof country.id);
            if (selectedCountry === country.id) {
                console.log("hello");
                // countryOption.selected = true;
            }
            countrySelect.appendChild(countryOption);
        });

        // Handle the change event on the country select box
    countrySelect.addEventListener('change', () => {
        // Clear the city and state select boxes
        citySelect.innerHTML = '<option value="">Select City</option>';
        stateSelect.innerHTML = '<option value="">Select State</option>';

        // Get the selected country ID
        const countryId = countrySelect.value;

        // Filter cities based on the selected country
        const filteredCities = cities.filter(city => city.country_id === parseInt(countryId));

        // Populate the city select box
        filteredCities.forEach(city => {
            const cityOption = document.createElement('option');
            cityOption.value = city.id;
            cityOption.textContent = city.name;
            if (selectedCity === city.id) {
                cityOption.selected = true;
            }
            citySelect.appendChild(cityOption);
        });
    });

    // Handle the change event on the city select box
    citySelect.addEventListener('change', () => {
        // Clear the state select box
        stateSelect.innerHTML = '<option value="">Select State</option>';

        // Get the selected city ID
        const cityId = citySelect.value;

        // Filter states based on the selected city
        const filteredStates = states.filter(state => state.city_id === parseInt(cityId));

        // Populate the state select box
        filteredStates.forEach(state => {
            const stateOption = document.createElement('option');
            stateOption.value = state.id;
            stateOption.textContent = state.name;
            if (selectedState === state.id) {
                stateOption.selected = true;
            }
            stateSelect.appendChild(stateOption);
        });
    });
        
</script>
@endsection
