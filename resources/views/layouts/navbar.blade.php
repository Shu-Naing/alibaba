@extends('layouts.app')

@section('content')
    <div class='dashboard'>
        <div class="dashboard-nav">
            <header>
                <a href="#!" class="menu-toggle">
                   <i class="bi bi-0-circle"></i>
                </a>
                <a href="#" class="brand-logo">                   
                    <span>{{ config('app.name', 'alibaba') }}</span>
                </a>
            </header>
            <nav class="dashboard-nav-list">
                <!-- <a href="#" class="dashboard-nav-item">
                    <i class="fas fa-home"></i>                    
                </a>
                <a href="#" class="dashboard-nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    dashboard
                </a> -->
                <a href="{{ route('users.index') }}" class="dashboard-nav-item"><i class="fa fa-users">                
                    </i> Admin 
                </a>
                <a href="{{ route('roles.index') }}" class="dashboard-nav-item"><i class="fa-solid fa-building-shield">              
                    </i> Roles & Permissions 
                </a>
                <a href="{{ route('categories.index') }}" class="dashboard-nav-item"><i class="fa-solid fa-building-shield">              
                    </i> Categories 
                </a>
                <!-- <div class='dashboard-nav-dropdown'>
                    <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-photo-video"></i> Media </a>
                    <div class='dashboard-nav-dropdown-menu'><a href="#" class="dashboard-nav-dropdown-item">All</a>
                        <a href="#" class="dashboard-nav-dropdown-item">Recent</a>
                        <a href="#" class="dashboard-nav-dropdown-item">Images</a>
                        <a href="#" class="dashboard-nav-dropdown-item">Video</a>
                    </div>
                </div>
                <div class='dashboard-nav-dropdown'>
                    <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-users"></i> Users </a>
                        <div class='dashboard-nav-dropdown-menu'>
                            <a href="#" class="dashboard-nav-dropdown-item">All</a>
                            <a href="#" class="dashboard-nav-dropdown-item">Subscribed</a>
                            <a href="#" class="dashboard-nav-dropdown-item">Non-subscribed</a>
                            <a href="#" class="dashboard-nav-dropdown-item">Banned</a>
                            <a href="#" class="dashboard-nav-dropdown-item">New</a>
                        </div>
                </div>
                <div class='dashboard-nav-dropdown'>
                    <a href="#!" class="dashboard-nav-item dashboard-nav-dropdown-toggle"><i class="fas fa-money-check-alt"></i> Payments </a>
                        <div class='dashboard-nav-dropdown-menu'>
                            <a href="#" class="dashboard-nav-dropdown-item">All</a>
                            <a href="#" class="dashboard-nav-dropdown-item">Recent</a>
                            <a href="#" class="dashboard-nav-dropdown-item"> Projections</a>
                        </div>
                </div> -->
                <!-- <a href="#" class="dashboard-nav-item">
                    <i class="fas fa-cogs"></i> Settings </a>
                    <a href="#" class="dashboard-nav-item"><i class="fas fa-user"></i> Profile </a>
                <div class="nav-item-divider"></div> -->

                <a class="dashboard-nav-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class ="fas fa-sign-out-alt"></i>{{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                </a>                
            </nav>
        </div>
        <div class='dashboard-app'>
            <header class='dashboard-toolbar'><a href="#!" class="menu-toggle"><i class="fas fa-bars"></i></a>
                <div class="container d-flex align-items-center justify-content-center">
                    @yield('cardtitle')
                </div>
            </header>
            <div class='dashboard-content'>
                @yield('cardbody')
            </div>
        </div>
    </div>
@endsection
