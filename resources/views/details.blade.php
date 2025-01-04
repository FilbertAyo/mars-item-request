@extends('layouts.petty_app')

@section('content')

    <div class="min-h-screen bg-gray-100">

        <main>
            <div class="wrapper">

                <nav id="sidebar" class="sidebar js-sidebar">
                    <div class="sidebar-content js-simplebar">
                        <a class="sidebar-brand" href="index.html">
                            <img src="/image/logo.png" alt="" class="img-fluid align-middle" style="max-height: 50px;">
                        </a>

                        <ul class="sidebar-nav">
                            <li class="sidebar-header">Pages</li>

                            <li class="sidebar-item active">
                                <a class="sidebar-link" href="{{ route('admin.index') }}">
                                    <span class="align-middle">Users</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ url('/branch_list') }}">
                                   <span class="align-middle">Branch</span>
                                </a>
                            </li>
                        </ul>



                        <div class="sidebar-cta-content text-center p-2 bg-danger">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">
                                    <strong class="d-inline-block">Logout</strong>
                                </button>
                            </form>
                        </div>

                    </div>
                </nav>
                <div class="main">
                    @include('layouts.navigation')

                    <main class="content">


                            <div class="mb-3" style="display: flex;justify-content: space-between;">
                                <h1 class="h3 d-inline align-middle">Profile</h1>
                                {{-- <a class="badge bg-dark text-white ms-2" href="upgrade-to-pro.html">
                                    Approval stage {{ $user->userType }}
                                </a> --}}
                                <a href="{{ route('admin.index') }}" type="button" class="btn btn-dark">
                                    Back
                                </a>

                            </div>
                            <div class="row">
                                <div class="col-md-8 col-xl-12">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Profile Details</h5>
                                        </div>
                                        <div class="card-body text-center">

                                            <img src="/image/prof.jpeg" alt="Christina Mason"
                                                class="img-fluid rounded-circle mb-2 align-middle" width="128" height="128" />

                                            <h5 class="card-title mb-0">{{ $user->name }}</h5>
                                            <div class="text-muted mb-2">{{ $user->email }} | {{ $user->phone }}

                                                <h5 class="card-title mb-0 text-success">{{ $user->branch }}</h5>
                                            </div>

                                            <div>
                                                <a class="btn btn-primary btn-sm"
                                                    href="#">{{ $user->department }}</a>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @if($user->status == 'active')

                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger flex">Deactivate user</button>
                            </form>

                            @else
                            <form action="{{ route('admin.activate', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success flex">Activate user</button>
                            </form>

                            @endif

                    </main>


                </div>
                <script src="js/app.js"></script>
            </div>
        </main>
    </div>

@endsection
