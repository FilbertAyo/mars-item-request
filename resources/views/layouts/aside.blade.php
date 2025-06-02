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
                                 <li>
                                     <a href="{{ route('petty.list') }}">
                                         <span class="sub-item">Initial Approval</span>
                                     </a>
                                 </li>

                                 <li>
                                     <a href="{{ route('petty.list') }}">
                                         <span class="sub-item">Last Approval</span>
                                     </a>
                                 </li>

                             </ul>
                         </div>
                     </li>
                 @endcan

                 @can('view cashflow movements')
                     <li class="nav-item {{ Request::routeIs('deposit.index') ? 'active' : '' }}">
                         <a data-bs-toggle="collapse" href="#base2">
                             <i class="bi bi-cash-coin"></i>
                             <p>Finance</p>
                             <span class="caret"></span>
                         </a>
                         <div class="collapse" id="base2">
                             <ul class="nav nav-collapse">
                                 <li>
                                     <a href="{{ route('petty.list') }}">
                                         <span class="sub-item">Payments</span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="{{ route('deposit.index') }}">
                                         <span class="sub-item">Cash Management</span>
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
                             <a data-bs-toggle="collapse" href="#base3">
                                 <i class="bi bi-clipboard2-check"></i>
                                 <p>Approval</p>
                                 <span class="caret"></span>
                             </a>
                             <div class="collapse" id="base3">
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
                             {{-- <span class="badge badge-success">4</span> --}}
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
                     <a href="{{ route('settings.routes') }}">
                         <i class="bi bi-geo-alt-fill"></i>
                         <p>Routes</p>
                     </a>
                 </li>

                 @endcan

             </ul>

         </div>
     </div>
 </div>
