<aside class="left-sidebar d-print-none">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between mt-3 mb-2s">
            <a href="/home" class="text-nowrap logo-img">
                <img src="{{ asset('images/logo_1.png') }}" alt="">
            </a>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                @can('home')
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('home') }}" class="nav-link sidebar-link align-middle {{ Route::is('home') ? 'active' : '' }}">
                        <i class="fs-4 bi-speedometer2"></i>
                        <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </li>
                @endcan
                @can('users.index')
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('users.index') }}"
                        class="nav-link sidebar-link align-middle {{ Route::is('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span class="d-none d-sm-inline">Users</span>
                    </a>
                </li>
                @endcan
                @can('roles.index')
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('roles.index') }}"
                        class="nav-link sidebar-link align-middle {{ Route::is('roles.*') ? 'active' : '' }}">
                        <i class="bi bi-person-lock"></i>
                        <span class="d-none d-sm-inline">Roles & Permissions</span>
                    </a>
                </li>  
                @endcan
                
                <!-- <li class="nav-item nav-small-cap">
                    <a href="#" class="nav-link sidebar-link align-middle">
                        <i class="fs-4 bi bi-person-lines-fill"></i>
                        <span class="d-none d-sm-inline">Contacts</span>
                    </a>
                </li> -->
                @if (auth()->user()->can('units.index') || auth()->user()->can('categories.index')
                 || auth()->user()->can('brands.index') || auth()->user()->can('products.index') 
                 || auth()->user()->can('products.create'))
                <li class="nav-item nav-small-cap">
                    <a href="#submenu2" data-bs-toggle="collapse" class="nav-link sidebar-link align-middle dropdownli">
                        <i class="fs-4 bi bi-box2"></i>
                        <span class="d-none d-sm-inline">Inventory</span>
                        <span class="showHide">+</span>
                    </a>
                    <ul class="collapse nav flex-column submenuParent" id="submenu2" data-bs-parent="#menu">
                        @can('units.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('units.index') }}"
                                class="nav-link sidebar-link {{ Route::is('units.*') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Units</span>
                            </a>
                        </li>
                        @endcan
                       
                        @can('categories.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('categories.index') }}"
                                class="nav-link sidebar-link {{ Route::is('categories.*') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Categories</span>
                            </a>
                        </li>
                        @endcan
                       
                        @can('brands.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('brands.index') }}"
                                class="nav-link sidebar-link {{ Route::is('brands.*') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Brands</span>
                            </a>
                        </li>   
                        @endcan
                       
                        @can('products.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('products.index') }}"
                                class="nav-link sidebar-link {{ Route::is('products.index') || Route::is('products.edit') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Product</span>
                            </a>
                        </li>   
                        @endcan
                       
                        @can('products.create')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('products.create') }}"
                                class="nav-link sidebar-link {{ Route::is('products.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Add Product</span>
                            </a>
                        </li>
                        @endcan

                        <li class="w-100 sidebar-item">
                            <a href="{{ route('size-variant.index') }}"
                                class="nav-link sidebar-link {{ Route::is('size-variant.*') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Size Variant</span>
                            </a>
                        </li>
                       
                    </ul>
                </li>
                @endif

                @if (auth()->user()->can('outlets.index') || auth()->user()->can('outlets.create')
                 || auth()->user()->can('machine.create') || auth()->user()->can('machine.index') 
                 || auth()->user()->can('distribute.create') || auth()->user()->can('distribute.index')
                 || auth()->user()->can('listdistributedetail') || auth()->user()->can('outletdistribute.index')
                 || auth()->user()->can('issue.index') || auth()->user()->can('sellingprice.index') || auth()->user()->can('outlethistory.history'))

                <li class="nav-item nav-small-cap">
                    <a href="#submenu1" data-bs-toggle="collapse" class="nav-link sidebar-link align-middle dropdownli">
                        <i class="fs-4 bi bi-shop"></i>
                        <span class="d-none d-sm-inline">Outlets</span>
                        <span class="showHide">+</span>
                    </a>
                    
                    <ul class="collapse nav flex-column submenuParent" id="submenu1" data-bs-parent="#menu">
                        @can('outlets.create')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outlets.create') }}"
                                class="nav-link sidebar-link {{ Route::is('outlets.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Create Outlet</span>
                            </a>
                        </li>
                        @endcan
                        @can('outlets.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outlets.index') }}"
                                class="nav-link sidebar-link {{ Route::is('outlets.index') || Route::is('outletstockoverview.*') || Route::is('outletdistribute.create') || Route::is('outletdistribute.edit') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Outlets</span>
                            </a>
                        </li>
                        @endcan
                        @can('machine.create')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('machine.create') }}"
                                class="nav-link sidebar-link {{ Route::is('machine.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Create Machine</span>
                            </a>
                        </li>
                        @endcan
                        @can('machine.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('machine.index') }}"
                                class="nav-link sidebar-link {{ Route::is('machine.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Machine</span>
                            </a>
                        </li>
                        @endcan
                        @can('distribute.create')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('distribute.create') }}"
                                class="nav-link sidebar-link {{ Route::is('distribute.create') || Route::is('distribute.edit') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Distribute Product</span>
                            </a>
                        </li>
                        @endcan
                        @can('distribute.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('distribute.index') }}"
                            class="nav-link sidebar-link {{ Route::is('distribute.index') || Route::is('distribute.show') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Distribute Product</span>
                            </a>
                        </li>
                        @endcan
                        @can('listdistributedetail')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('listdistributedetail') }}"
                            class="nav-link sidebar-link {{ Route::is('listdistributedetail') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">List Distribute Detail</span>
                            </a>
                        </li>
                        @endcan
                        @can('outletdistribute.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outletdistribute.index')}}"
                            class="nav-link sidebar-link {{ Route::is('outletdistribute.index') || Route::is('outletdistribute.show') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">List Receive Distribute Product</span>
                            </a>
                        </li>
                        @endcan
                        @can('issue.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('issue.index')}}"
                            class="nav-link sidebar-link {{ Route::is('issue.index') || Route::is('issue.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">List Issue Distribute Product</span>
                            </a>
                        </li>
                        @endcan
                        
                        @can('outlethistory.history')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outlethistory.history') }}"
                                class="nav-link sidebar-link {{ Route::is('outlethistory.history') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">Outlet History</span>
                            </a>
                        </li>
                        @endcan
                        @can('outletleveloverview.create')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outletleveloverview.create') }}"
                                class="nav-link sidebar-link {{ Route::is('outletleveloverview.create') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">Opening Quantity (Outlet)</span>
                            </a>
                        </li>
                        @endcan

                       
                        @can('sellingprice.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('sellingprice.index') }}"
                                class="nav-link sidebar-link {{ Route::is('sellingprice.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Selling Price Group</span>
                            </a>
                        </li>
                        @endcan
                        @can('adjustment.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('adjustment.index') }}"
                                class="nav-link sidebar-link {{ Route::is('adjustment.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Adjustment</span>
                            </a>
                        </li>
                        @endcan
                        @can('damage.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('damage.index') }}"
                                class="nav-link sidebar-link {{ Route::is('damage.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Damage</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif

                @if (auth()->user()->can('outlets.create') || auth()->user()->can('purchased-price-history.index'))

                    <li class="nav-item nav-small-cap">
                        <a href="#submenuPurchase" data-bs-toggle="collapse" class="nav-link sidebar-link align-middle dropdownli">
                            <i class="fs-4 bi bi-shop"></i>
                            <span class="d-none d-sm-inline">Purchase</span>
                            <span class="showHide">+</span>
                        </a>
                        
                        <ul class="collapse nav flex-column submenuParent" id="submenuPurchase" data-bs-parent="#menu">
                            @can('purchase.create')
                                <li class="w-100 sidebar-item">
                                    <a href="{{ route('purchase.create') }}"
                                        class="nav-link sidebar-link {{ Route::is('purchase.create') ? 'active' : '' }}">
                                        <hr>
                                        <span class="d-none d-sm-inline">Add Purchase</span>
                                    </a>
                                </li>
                            @endcan
                            @can('purchase.index')
                                <li class="w-100 sidebar-item">
                                    <a href="{{ route('purchase.index') }}"
                                        class="nav-link sidebar-link {{ Route::is('purchase.index') ? 'active' : '' }}">
                                        <hr>
                                        <span class="d-none d-sm-inline">Purchase(GRN Report)</span>
                                    </a>
                                </li>
                            @endcan
                            @can('purchased-price-history.index')
                                <li class="w-100 sidebar-item">
                                    <a href="{{ route('purchased-price-history.index') }}"
                                        class="nav-link sidebar-link {{ Route::is('purchased-price-history.index') ? 'active' : '' }}">
                                        <hr>
                                        <span class="d-none d-sm-inline">Purchased Price History</span>
                                    </a>
                                </li>
                            @endcan
                            @can('report.price-changed-history')
                                <li class="w-100 sidebar-item">
                                    <a href="{{ route('report.price-changed-history') }}"
                                        class="nav-link sidebar-link {{ Route::is('report.price-changed-history') ? 'active' : '' }}">
                                        <hr>
                                        <span class="d-none d-sm-inline">Changed Price History</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @can('pos.index')
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('pos.index') }}" 
                        class="nav-link sidebar-link align-middle {{ Route::is('pos.index') ? 'active' : '' }}">
                        <i class="fs-4 bi bi-receipt"></i>
                        <span class="d-none d-sm-inline">Pos</span></a>
                </li>
                @endcan

                @can('sell.index')
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('sell.index') }}" 
                        class="nav-link sidebar-link align-middle {{ Route::is('sell.index') ? 'active' : '' }}">
                        <i class="fs-4 bi bi-receipt"></i>
                        <span class="d-none d-sm-inline">Sell</span></a>
                </li>
                @endcan

                @can('stockalert.index')
                <li class="nav-item nav-small-cap">
                    <a href="{{ route('stockalert.index') }}" 
                        class="nav-link sidebar-link align-middle {{ Route::is('stockalert.index') ? 'active' : '' }}">
                        <i class="fs-4 bi bi-receipt"></i>
                        <span class="d-none d-sm-inline">Stock Alert</span></a>
                </li>
                @endcan
                
               
                @if (auth()->user()->can('report.products') || auth()->user()->can('report.outletstockoverview') || auth()->user()->can('outletstockhistory.index') )
                <li class="nav-item nav-small-cap">
                    <a href="#reportmenu1" data-bs-toggle="collapse" class="nav-link sidebar-link align-middle dropdownli">
                        <i class="fs-4 bi bi-file-earmark-medical"></i>
                        <span class="d-none d-sm-inline">Reports</span>
                        <span class="showHide">+</span>
                    </a>
                    <ul class="collapse nav flex-column submenuParent" id="reportmenu1" data-bs-parent="#menu">
                        
                        @can('report.products')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('report.products') }}" 
                                class="nav-link sidebar-link {{ Route::is('report.products') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline">Product Reports</span>
                            </a>
                        </li>
                        @endcan
                       
                        @can('outletstockhistory.index')
                            <li class="w-100 sidebar-item">
                                <a href="{{ route('outletstockhistory.index') }}"
                                    class="nav-link sidebar-link {{ Route::is('outletstockhistory.index') ? 'active' : '' }}">
                                    <hr>
                                    <span class="d-none d-sm-inline text-wrap">Outlet Stock History (Machine)</span>
                                </a>
                            </li> 
                        @endcan
                        
                        @can('report.outletstockoverview')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('report.outletstockoverview')}}"
                                class="nav-link sidebar-link {{ Route::is('report.outletstockoverview') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">Outlet Stock Overview Reports (Machine)</span>
                            </a>
                        </li>
                        @endcan
                        
                        @can('report.bodanddepartment')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('report.bodanddepartment')}}"
                                class="nav-link sidebar-link {{ Route::is('report.bodanddepartment') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">BOD & Department </span>
                            </a>
                        </li>
                        @endcan

                        @can('outletlevelhistory.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outletlevelhistory.index') }}"
                                class="nav-link sidebar-link {{ Route::is('outletlevelhistory.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">Outlet Stock History (Store)</span>
                            </a>
                        </li>
                        @endcan


                        @can('outletleveloverview.index')
                        <li class="w-100 sidebar-item">
                            <a href="{{ route('outletleveloverview.index') }}"
                                class="nav-link sidebar-link {{ Route::is('outletleveloverview.index') ? 'active' : '' }}">
                                <hr>
                                <span class="d-none d-sm-inline text-wrap">Outlet Stock Overview (Store)</span>
                            </a>
                        </li>
                        @endcan
                      
                    </ul>
                </li>
                @endif
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
