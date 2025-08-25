 <div class="sidebar" data-background-color="dark">
     <div class="sidebar-logo">
         <!-- Logo Header -->
         <div class="logo-header" data-background-color="dark">
             <a href="{{ route('dashboard') }}" class="logo">
                 <img src="{{ asset('image/logo.png') }}" alt="navbar brand" class="navbar-brand" height="50" />
             </a>
             <div class="nav-toggle">
                 <button class="btn btn-toggle toggle-sidebar">
                     <i class="gg-menu-right"></i>
                 </button>
                 <button class="btn btn-toggle sidenav-toggler">
                     <i class="gg-menu-left"></i>
                 </button>
             </div>
             <button class="topbar-toggler more">
                 <i class="gg-more-vertical-alt"></i>
             </button>
         </div>
         <!-- End Logo Header -->
     </div>
     <div class="sidebar-wrapper scrollbar scrollbar-inner">
         <div class="sidebar-content">
             <ul class="nav nav-secondary">
                 <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
                     <a href="{{ route('dashboard') }}">
                         <i class="bi bi-house-fill"></i>
                         <p>Dashboard</p>
                     </a>

                 </li>
                 <li class="nav-section">
                     <span class="sidebar-mini-icon">
                         <i class="fa fa-ellipsis-h"></i>
                     </span>
                     <h4 class="text-section">Petty Cash</h4>
                 </li>


                 <li class="nav-item {{ Request::routeIs('petty.index') ? 'active' : '' }}">
                     <a href="{{ route('petty.index') }}">
                         <i class="bi bi-wallet-fill"></i>
                         <p>Requests</p>
                     </a>
                 </li>


                 @can('view requested pettycash')
                     <li class="nav-item">
                         <a data-bs-toggle="collapse" href="#base">
                             <i class="bi bi-clipboard-check-fill"></i>
                             <p>Approvals</p>
                             <span class="caret"></span>
                         </a>
                         <div class="collapse" id="base">
                             <ul class="nav nav-collapse">
                                 @can('first pettycash approval')
                                     <li>
                                         <a href="{{ route('petty.list') }}">
                                             <span class="sub-item">Initial Approval</span>
                                             @if ($pendingPettiesCount > 0)
                                                 <span class="badge badge-danger">{{ $pendingPettiesCount }}</span>
                                             @endif
                                         </a>
                                     </li>
                                 @endcan

                                 @can('last pettycash approval')
                                     <li>
                                         <a href="{{ route('all.pettycash') }}">
                                             <span class="sub-item">Last Approval</span>
                                             @if ($processingPettiesCount > 0)
                                                 <span class="badge badge-warning">{{ $processingPettiesCount }}</span>
                                             @endif
                                         </a>
                                     </li>
                                 @endcan

                             </ul>
                         </div>
                     </li>
                 @endcan

                 @can('view cashflow movements')

                     <li class="nav-item {{ Request::routeIs('deposit.index') ? 'active' : '' }}">
                         <a data-bs-toggle="collapse" href="#submenu">
                             <i class="bi bi-cash-coin"></i>
                             <p>Finance</p>
                             <span class="caret"></span>
                         </a>
                         <div class="collapse" id="submenu">
                             <ul class="nav nav-collapse">
                                 <li>
                                     <a href="{{ route('petty.cashier') }}">
                                         <span class="sub-item">Payments</span>
                                         @if ($approvedPettiesCount > 0)
                                             <span class="badge badge-success">{{ $approvedPettiesCount }}</span>
                                         @endif
                                     </a>
                                 </li>
                                 <li>
                                     <a data-bs-toggle="collapse" href="#subnav1">
                                         <span class="sub-item">Transactions</span>
                                         <span class="caret"></span>
                                     </a>
                                     <div class="collapse" id="subnav1">
                                         <ul class="nav nav-collapse subnav">
                                             <li>
                                                 <a href="{{ route('deposit.index') }}">
                                                     <span class="sub-item">Cash Management</span>
                                                 </a>
                                             </li>
                                             <li>
                                                 <a href="{{ route('cashflow.index') }}">
                                                     <span class="sub-item">Cash Flow</span>
                                                 </a>
                                             </li>
                                         </ul>
                                     </div>
                                 </li>


                             </ul>
                         </div>
                     </li>

                 @endcan

                 @can('view requested pettycash')
                     <li class="nav-item {{ Request::routeIs('replenishment.index') ? 'active' : '' }}">
                         <a data-bs-toggle="collapse" href="#base3">
                             <i class="bi bi-file-ruled"></i>
                             <p>Replenishments</p>

                             <span class="caret"></span>
                         </a>
                         <div class="collapse" id="base3">
                             <ul class="nav nav-collapse">
                                 <li>
                                     <a href="{{ route('replenishment.index') }}">
                                         <span class="sub-item">Reports</span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="{{ route('replenishment.pettycash') }}">
                                         <span class="sub-item">Petty cash</span>
                                     </a>
                                 </li>

                             </ul>
                         </div>
                     </li>
                 @endcan

                 @can('request item purchase')

                     <li class="nav-section">
                         <span class="sidebar-mini-icon">
                             <i class="fa fa-ellipsis-h"></i>
                         </span>
                         <h4 class="text-section">Item Request</h4>
                     </li>


                     <li class="nav-item {{ Request::routeIs('item-request.index') ? 'active' : '' }}">
                         <a href="{{ route('item-request.index') }}">
                             <i class="bi bi-wallet2"></i>
                             <p>Requests</p>
                             {{-- <span class="badge badge-success">4</span> --}}
                         </a>
                     </li>

                     @can('approve item purchase')
                         <li class="nav-item {{ Request::routeIs('general.index') ? 'active' : '' }}">
                             <a data-bs-toggle="collapse" href="#base8">
                                 <i class="bi bi-clipboard2-check"></i>
                                 <p>Approval</p>
                                 <span class="caret"></span>
                             </a>
                             <div class="collapse" id="base8">
                                 <ul class="nav nav-collapse">

                                     <li>
                                         <a href="{{ route('item-request.list') }}">
                                             <span class="sub-item">Confirmation</span>
                                         </a>
                                     </li>

                                 </ul>
                             </div>
                         </li>
                     @endcan

                     <li class="nav-item">
                         <a href="{{ route('justify.index') }}">
                             <i class="bi bi-circle-square"></i>
                             <p>Justifications</p>
                         </a>
                     </li>

                 @endcan


                 @can('warranty management')
                 <li class="nav-section">
                     <span class="sidebar-mini-icon">
                         <i class="fa fa-ellipsis-h"></i>
                     </span>
                     <h4 class="text-section">Warranty</h4>
                 </li>


                 <li class="nav-item {{ Request::routeIs('warranty.index') ? 'active' : '' }}">
                     <a href="{{ route('warranty.index') }}">
                         <i class="bi bi-journal-text"></i>
                         <p>Collections</p>
                     </a>
                 </li>
                 @endcan

                 @can('mars website management')
                     <li class="nav-section">
                         <span class="sidebar-mini-icon">
                             <i class="fa fa-ellipsis-h"></i>
                         </span>
                         <h4 class="text-section">Website Settings</h4>
                     </li>

                     <li class="nav-item {{ Request::routeIs('website.settings') ? 'active' : '' }}">
                        <a href="{{ route('website.settings') }}">
                            <i class="bi bi-sliders"></i>
                            <p>Home Settings</p>
                        </a>
                    </li>

                     <li class="nav-item {{ Request::routeIs('branch.settings') ? 'active' : '' }}">
                        <a href="{{ route('branch.settings') }}">
                            <i class="bi bi-sliders"></i>
                            <p>Branches</p>
                        </a>
                    </li>

                     <li class="nav-item {{ Request::routeIs('catalogues.index') ? 'active' : '' }}">
                         <a href="{{ route('catalogues.index') }}">
                            <i class="bi bi-tv"></i>
                             <p>Catalogues</p>
                         </a>
                     </li>
                 @endcan


                 @can('view reports')
                     <li class="nav-section">
                         <span class="sidebar-mini-icon">
                             <i class="fa fa-ellipsis-h"></i>
                         </span>
                         <h4 class="text-section">Reports</h4>
                     </li>

                     <li class="nav-item">
                         <a href="{{ route('reports') }}">
                             <i class="bi bi-book-half"></i>
                             <p>Reports</p>
                         </a>
                     </li>
                 @endcan

                 @can('view settings')
                     <li class="nav-section">
                         <span class="sidebar-mini-icon">
                             <i class="fa fa-ellipsis-h"></i>
                         </span>
                         <h4 class="text-section">Settings</h4>
                     </li>

                     <li class="nav-item {{ Request::routeIs('admin.index') ? 'active' : '' }}">
                         <a href="{{ route('admin.index') }}">
                             <i class="bi bi-people"></i>
                             <p>Users</p>
                         </a>
                     </li>

                     <li class="nav-item {{ Request::routeIs('branches') ? 'active' : '' }}">
                         <a href="{{ route('branches') }}">
                             <i class="bi bi-microsoft"></i>
                             <p>Branches</p>
                         </a>
                     </li>

                     <li class="nav-item {{ Request::routeIs('settings.routes') ? 'active' : '' }}">
                         <a data-bs-toggle="collapse" href="#base4">
                             <i class="bi bi-geo-alt-fill"></i>
                             <p>Transport</p>
                             <span class="caret"></span>
                         </a>
                         <div class="collapse" id="base4">
                             <ul class="nav nav-collapse">

                                 <li>
                                     <a href="{{ route('settings.routes') }}">
                                         <span class="sub-item">Routes</span>
                                     </a>
                                 </li>

                                 <li>
                                     <a href="{{ route('settings.destination') }}">
                                         <span class="sub-item">Destinations</span>
                                     </a>
                                 </li>

                             </ul>
                         </div>
                     </li>
                 @endcan

             </ul>

         </div>
     </div>
 </div>
