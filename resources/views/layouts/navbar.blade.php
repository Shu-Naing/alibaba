@extends('layouts.app')
@section('content')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between mt-3 mb-2s">
          <a href="/home" class="text-nowrap logo-img">
            <!-- <h1>Alibaba</h1> -->
            <img src="{{ asset('images/logo_1.png') }}" alt="">
          </a>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-item nav-small-cap">
              <a href="#" class="nav-link align-middle px-0">
                <i class="fs-4 bi-speedometer2"></i>
                <span class="d-none d-sm-inline">Dashboard</span>
              </a>
            </li>
            <li class="nav-item nav-small-cap">
              <a href="#" class="nav-link align-middle px-0">
                <i class="fs-4 bi bi-person"></i>
                <span class="d-none d-sm-inline">User</span>
              </a>
            </li>
            <li class="nav-item nav-small-cap">
              <a href="#" class="nav-link align-middle px-0">
                <i class="fs-4 bi bi-person-lines-fill"></i>
                <span class="d-none d-sm-inline">Contacts</span>
              </a>
            </li>
            <li class="nav-item nav-small-cap">
              <a
                href="#submenu2"
                data-bs-toggle="collapse"
                class="nav-link px-0 align-middle dropdownli">
                <i class="fs-4 bi bi-box2"></i>
                <span class="d-none d-sm-inline">Inventory</span>
                <span class="showHide">+</span>
              </a>
              <ul
                class="collapse nav flex-column submenuParent"
                id="submenu2"
                data-bs-parent="#menu">
                <li class="w-100 sidebar-item">
                  <a href="{{ route('units.index') }}" class="nav-link sidebar-link">
                    <hr>
                    <span class="d-none d-sm-inline">Units</span> 
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('categories.index') }}" class="nav-link sidebar-link ">
                    <hr>
                    <span class="d-none d-sm-inline">Categories</span>
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('brands.index') }}" class="nav-link sidebar-link ">
                    <hr>
                    <span class="d-none d-sm-inline">Brands</span>
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('products.index') }}" class="nav-link sidebar-link ">
                    <hr>
                    <span class="d-none d-sm-inline">List Product</span>
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('products.create') }}" class="nav-link sidebar-link ">
                    <hr>
                    <span class="d-none d-sm-inline">Add Product</span>
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="#" class="nav-link sidebar-link ">
                    <hr>
                    <span class="d-none d-sm-inline">Outlet Stocks</span>
                  </a>
                </li>
              </ul>
            </li>
             <li class="nav-item nav-small-cap">
              <a
                href="#submenu1"
                data-bs-toggle="collapse"
                class="nav-link px-0 align-middle dropdownli">
                <i class="fs-4 bi bi-shop"></i>
                <span class="d-none d-sm-inline">Outlets</span>
                <span class="showHide">+</span>
              </a>
              <ul
                class="collapse nav flex-column submenuParent"
                id="submenu1"
                data-bs-parent="#menu">
                <li class="w-100 sidebar-item">
                  <a href="{{ route('outlets.index') }}" class="nav-link sidebar-link {{ Route::is('outlets.index') ? 'active' : ''  }}">
                    <hr>
                    <span class="d-none d-sm-inline">List Outlets</span> 
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('outlets.create') }}" class="nav-link sidebar-link {{ Route::is('outlets.create') ? 'active' : '' }}">
                    <hr>
                    <span class="d-none d-sm-inline">Create Outlet</span>
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('machine.create') }}" class="nav-link sidebar-link {{ Route::is('machine.create') ? 'active' : '' }}">
                    <hr>
                    <span class="d-none d-sm-inline">Create Machine</span> 
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('distribute.create') }}" class="nav-link sidebar-link {{ Route::is('distribute.create') ? 'active' : '' }}">
                    <hr>
                    <span class="d-none d-sm-inline">Distribute Product</span>
                  </a>
                </li>
                <li class="w-100 sidebar-item">
                  <a href="{{ route('sellingprice.index') }}" class="nav-link sidebar-link">
                    <hr>
                    <span class="d-none d-sm-inline">Selling Price Group</span> 
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item nav-small-cap">
              <a href="#" class="nav-link px-0 align-middle">
                <i class="fs-4 bi bi-cart-check"></i>
                <span class="d-none d-sm-inline">Purchase</span></a
              >
            </li>
            <li class="nav-item nav-small-cap">
              <a href="#" class="nav-link px-0 align-middle">
                <i class="fs-4 bi bi-receipt"></i>
                <span class="d-none d-sm-inline">Pos</span></a
              >
            </li>
            <li class="nav-item nav-small-cap">
              <a href="#" class="nav-link px-0 align-middle">
                <i class="fs-4 bi bi-cash-coin"></i>
                <span class="d-none d-sm-inline">Sell</span>
              </a>
            </li>
            <li class="nav-item nav-small-cap">
              <a href="#" class="nav-link px-0 align-middle">
                <i class="fs-4 bi bi-file-earmark-medical"></i>
                <span class="d-none d-sm-inline">Reports</span>
              </a>
            </li>
          </ul>
          <!-- <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./index.html" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">UI COMPONENTS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('categories.index') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Categories</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-alert-circle"></i>
                </span>
                <span class="hide-menu">Users</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-card.html" aria-expanded="false">
                <span>
                  <i class="ti ti-cards"></i>
                </span>
                <span class="hide-menu">Card</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-forms.html" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Forms</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-typography.html" aria-expanded="false">
                <span>
                  <i class="ti ti-typography"></i>
                </span>
                <span class="hide-menu">Typography</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">AUTH</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-login.html" aria-expanded="false">
                <span>
                  <i class="ti ti-login"></i>
                </span>
                <span class="hide-menu">Login</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./authentication-register.html" aria-expanded="false">
                <span>
                  <i class="ti ti-user-plus"></i>
                </span>
                <span class="hide-menu">Register</span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">EXTRA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./icon-tabler.html" aria-expanded="false">
                <span>
                  <i class="ti ti-mood-happy"></i>
                </span>
                <span class="hide-menu">Icons</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./sample-page.html" aria-expanded="false">
                <span>
                  <i class="ti ti-aperture"></i>
                </span>
                <span class="hide-menu">Sample Page</span>
              </a>
            </li>
          </ul> -->
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light justify-content-end">
            @yield('cardtitle')
        </nav>
      </header>
      <!--  Header End -->
      <div>
        <!--  Row 1 -->
        
        @yield('cardbody')
      </div>
    </div>
  </div>
@endsection
