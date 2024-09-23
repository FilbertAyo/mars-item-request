<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mars communications | Purchase request</title>

    {{-- added --}}
    <link rel="canonical" href="{{ asset('https://v5.getbootstrap.com/docs/5.0/examples/dashboard/') }}">

    <link rel="preconnect" href="{{ asset('https://fonts.gstatic.com') }}">
    <link rel="shortcut icon" href="{{ asset('static/img/icons/icon-48x48.png') }}" />
    <link rel="canonical" href="{{ asset('https://demo-basic.adminkit.io/') }}" />
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">



    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="{{ asset('https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap') }}" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">


        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="wrapper">

                <nav id="sidebar" class="sidebar js-sidebar">
                    <div class="sidebar-content js-simplebar">
                        <a class="sidebar-brand" href="index.html">
                            <img src="/image/logo.png" alt="" class="img-fluid align-middle" style="max-height: 50px;">
                        </a>

                        <ul class="sidebar-nav">
                            <li class="sidebar-header">Pages</li>

                            <li class="sidebar-item {{ Request::routeIs('admin.index') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('admin.index') }}">
                                    <span class="align-middle">Users</span>
                                </a>
                            </li>

                            {{-- <li class="sidebar-item {{ Request::routeIs('exbitor.index') ? 'active' : '' }}">
              <a class="sidebar-link" href="{{ route('exbitor.index') }}">
                 <span class="align-middle">Exhibitors</span>
              </a>
          </li>

          <li class="sidebar-item {{ Request::routeIs('speaker.index') ? 'active' : '' }}">
              <a class="sidebar-link" href="{{ route('speaker.index') }}">
                  <span class="align-middle">Speakers</span>
              </a>
          </li>
          <li class="sidebar-item {{ Request::routeIs('visitor.index') ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('visitor.index') }}">
                <span class="align-middle">Visitors</span>
            </a>
        </li>
          <li class="sidebar-item {{ Request::routeIs('location.index') ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('location.index') }}">
                 <span class="align-middle">Location</span>
            </a>
        </li>
        <li class="sidebar-item {{ Request::routeIs('meeting.index') ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ route('meeting.index') }}">
                 <span class="align-middle">Meetings</span>
            </a>
        </li> --}}
                        </ul>



                        <div class="sidebar-cta-content">
                            <strong class="d-inline-block mb-2">MC2024</strong>

                            <div class="d-grid">
                                <a href="upgrade-to-pro.html" class="btn btn-success"></a>
                            </div>
                        </div>

                    </div>
                </nav>
                <div class="main">
                    @include('layouts.navigation')

                    <main class="content">
                        <div class="container-fluid p-0">

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
                            
                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger flex">Deactivate user</button>
                            </form>
                          
                        </div>
                    </main>


                </div>


                <script src="js/app.js"></script>




            </div>
        </main>
    </div>


    <script src="{{ asset('static/js/app.js') }}"></script>

</body>

</html>
