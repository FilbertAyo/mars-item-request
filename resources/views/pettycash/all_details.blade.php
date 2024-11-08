<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mars communications </title>

     {{-- added --}}
     <link rel="canonical" href="{{ asset('https://v5.getbootstrap.com/docs/5.0/examples/dashboard/') }}">

     <link rel="preconnect" href="{{ asset('https://fonts.gstatic.com') }}">
     <link rel="shortcut icon" href="{{ asset('static/img/icons/icon-48x48.png') }}" />
     <link rel="canonical" href="{{ asset('https://demo-basic.adminkit.io/') }}" />
     <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">



     <link rel="stylesheet" href="{{ asset('https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css') }}"
         integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
     <script src="{{ asset('https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js') }}"
         integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
     </script>
     <script src="{{ asset('https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js') }}"
         integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
     </script>

     <!-- Fonts -->
     <link rel="preconnect" href="{{ asset('https://fonts.bunny.net') }}">
     <link href="{{ asset('https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap') }}" rel="stylesheet" />

     <!-- Scripts -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])

     {{-- search  --}}
     <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}"></script>

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
                            <img src="/image/logo.png" alt="" class="img-fluid align-middle"
                                style="max-height: 50px;">
                        </a>

                        <ul class="sidebar-nav">
                            <li class="sidebar-header">Pages</li>

                            <li class="sidebar-item active">
                                <a class="sidebar-link" href="{{ route('petty.index') }}">
                                    <span class="align-middle">Petty Cash</span>
                                </a>
                            </li>

                        </ul>



                        <div class="sidebar-cta-content">
                            <strong class="d-inline-block mb-2">MC2024</strong>

                            <div class="d-grid">
                                <a href="upgrade-to-pro.html" class="btn btn-primary"></a>
                            </div>
                        </div>

                    </div>
                </nav>
                <div class="main">
                    @include('layouts.navigation')



                    <main class="content">
                        <div class="container-fluid p-0">

                            <div class="mb-1" style="display: flex;justify-content: space-between;">

                                <h1 class="h3">Petty cash details

                                    @if ($request->status == 'pending')
                                    <span
                                        class="badge bg-info text-white text-sm ms-2">{{ $request->status }}</span>
                                @elseif($request->status == 'processing')
                                    <span
                                        class="badge bg-warning text-white text-sm ms-2">{{ $request->status }}</span>
                                @elseif($request->status == 'rejected')
                                    <span
                                        class="badge bg-danger text-white text-sm ms-2">{{ $request->status }}</span>
                                @else
                                    <span
                                        class="badge bg-success text-white text-sm ms-2">{{ $request->status }}</span>
                                @endif
                                </h1>

                                    <a href="{{ route('petty.index') }}" type="button" class="btn btn-dark">
                                    Back
                                </a>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                                    <div class="container mt-2">
                                        <div class="card shadow-sm border-0">

                                            <div class="card-body">

                                                <div class="mb-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="fs-4"><strong>Petty cash for:</strong> {{ $request->name }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Petty cash type:</strong> {{ $request->request_type }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-4">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <p><strong>Reason:</strong> {{ $request->reason }}</p>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="mb-4">
                                                    <h5 class="text-secondary mb-3 text-primary"><strong>List of Items ,Service or Routes</strong></h5>

                                                    @if ($request->pettyLists->count() > 0)
                                                        <ul class="list-group">
                                                            @foreach ($request->pettyLists as $item)
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    {{ $item->item_name }}
                                                                    <span class="badge bg-danger rounded-pill"><i class="fa fa-check"></i></span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-muted">No items associated with this request.</p>
                                                    @endif
                                                </div>

                                                <!-- Back Button -->
                                                <div class="text-end">
                                                   Amount Needed: <div class="btn btn-secondary">TZS {{ number_format($request->amount, 2) }}/=</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </main>


    <script src="{{ asset('static/js/app.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
