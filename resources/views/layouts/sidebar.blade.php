<aside class="left-sidebar d-print-none">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between mt-3 mb-2s">
            <a href="/home" class="text-nowrap logo-img">
                <img src="{{ asset('images/logo_1.png') }}" alt="">
            </a>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-item nav-small-cap">
                    <a href="#" class="nav-link sidebar-link align-middle">
                        <i class="fs-4 bi-speedometer2"></i>
                        <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('users.index') }}"
                        class="nav-link sidebar-link align-middle {{ Route::is('users.index') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span class="d-none d-sm-inline">Users</span>
                    </a>
                </li>
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('roles.index') }}"
                        class="nav-link sidebar-link align-middle {{ Route::is('roles.index') ? 'active' : '' }}">
                        <i class="bi bi-person-lock"></i>
                        <span class="d-none d-sm-inline">Roles & Permissions</span>
                    </a>
                </li>
                <li class="nav-item nav-small-cap">
                    <a href="#" class="nav-link sidebar-link align-middle">
                        <i class="fs-4 bi bi-person-lines-fill"></i>
                        <span class="d-none d-sm-inline">Contacts</span>
                    </a>
                </li>
                <li class="nav-item nav-small-cap">
                    <a href="#submenu2" data-bs-toggle="collapse" class="nav-link sidebar-link align-middle dropdownli">
                        <i class="fs-4 bi bi-box2"></i>
                        <span class="d-none d-sm-inline">Inventory</span>
                        <span class="showHide">+</span>
                    </a>
                    <ul class="collapse nav flex-column submenuParent" id="submenu2" data-bs-parent="#menu">
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('units.index') }}"
                                class="nav-link sidebar-link {{ Route::is('units.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Units</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('categories.index') }}"
                                class="nav-link sidebar-link {{ Route::is('categories.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Categories</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('brands.index') }}"
                                class="nav-link sidebar-link {{ Route::is('brands.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Brands</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('products.index') }}"
                                class="nav-link sidebar-link {{ Route::is('products.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Product</span>
                            </a>
                        </li>

                        <li class="w-100 sidebar-item">
                            <a href="{{ route('products.create') }}"
                                class="nav-link sidebar-link {{ Route::is('products.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Add Product</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-small-cap">
                    <a href="#submenu1" data-bs-toggle="collapse" class="nav-link sidebar-link align-middle dropdownli">
                        <i class="fs-4 bi bi-shop"></i>
                        <span class="d-none d-sm-inline">Outlets</span>
                        <span class="showHide">+</span>
                    </a>
                    <ul class="collapse nav flex-column submenuParent" id="submenu1" data-bs-parent="#menu">
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outlets.index') }}"
                                class="nav-link sidebar-link {{ Route::is('outlets.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Outlets</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outlets.create') }}"
                                class="nav-link sidebar-link {{ Route::is('outlets.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Create Outlet</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('machine.index') }}"
                                class="nav-link sidebar-link {{ Route::is('machine.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Machine</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('machine.create') }}"
                                class="nav-link sidebar-link {{ Route::is('machine.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Create Machine</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('distribute.create') }}"
                                class="nav-link sidebar-link {{ Route::is('distribute.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Distribute Product</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('distribute.index') }}"
                                class="nav-link sidebar-link">
                                <hr>
                                <span class="d-none d-sm-inline">List Distribute Product</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('sellingprice.index') }}"
                                class="nav-link sidebar-link {{ Route::is('sellingprice.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Selling Price Group</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('pos.index') }}" 
                        class="nav-link sidebar-link align-middle {{ Route::is('pos.index') ? 'active' : '' }}">
                        <i class="fs-4 bi bi-receipt"></i>
                        <span class="d-none d-sm-inline">Pos</span></a>
                </li>
                <!-- <li class="nav-item nav-small-cap">
                    <a href="#" class="nav-link px-0 align-middle">
                        <i class="fs-4 bi bi-file-earmark-medical"></i>
                        <span class="d-none d-sm-inline">Reports</span>
                    </a>
                </li> -->
                <li class="nav-item nav-small-cap">
                    <a href="#reportmenu1" data-bs-toggle="collapse" class="nav-link sidebar-link align-middle dropdownli">
                        <i class="fs-4 bi bi-file-earmark-medical"></i>
                        <span class="d-none d-sm-inline">Reports</span>
                        <span class="showHide">+</span>
                    </a>
                    <ul class="collapse nav flex-column submenuParent" id="reportmenu1" data-bs-parent="#menu">
                        <!-- <li class="w-100 sidebar-item">
                            <a href="{{ route('report.machine')}}"
                                class="nav-link sidebar-link">
                                <hr>
                                <span class="d-none d-sm-inline">Outlet Reports</span>
                            </a>
                        </li> -->
                        <li class="w-100 sidebar-item">
                            <a href="#" class="nav-link sidebar-link">
                                <hr>
                                <span class="d-none d-sm-inline">Product Reports</span>
                            </a>
                        </li>
                         <li class="w-100 sidebar-item">
                            <a href="{{ route('report.outletstockoverview')}}"
                                class="nav-link sidebar-link {{ Route::is('report.outletstockoverview') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">Outlet Stock Overview Reports</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outletdistribute.index')}}"
                                class="nav-link sidebar-link {{ Route::is('outletdistribute.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">List Outlet Distribute Prodcut</span>
                            </a>
                        </li>
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outletstockhistory.index') }}"
                                class="nav-link sidebar-link {{ Route::is('outletstockhistory.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Outlet Stock History</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-small-cap logout-sidebar">
                    {!! Form::open(['url' => 'logout', 'method' => 'POST']) !!}
                    <i class="fs-4 bi bi-box-arrow-right"></i>
                    <button type="submit"
                        class="nav-link btn btn-link fw-bolder px-0 align-middle d-inline-block">Logout</button>
                    {!! Form::close() !!}
                </li>

            </ul>
        </nav>
    </div>
</aside>
