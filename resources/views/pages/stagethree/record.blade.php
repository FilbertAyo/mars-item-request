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
                            <img src="/image/logo.png" alt="" class="img-fluid align-middle"
                                style="max-height: 50px;">
                        </a>

                        <ul class="sidebar-nav">
                            <li class="sidebar-header">Pages</li>

                            <li class="sidebar-item {{ Request::routeIs('general.index') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('general.index') }}">
                                    <span class="align-middle">Item purchase</span>
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
                    
                            <!-- Page Title and Department Badge -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h1 class="h3 mb-0">Item Purchase Records 
                                    <span class="badge bg-primary ms-2">{{ Auth::user()->department }}</span>
                                </h1>
                            </div>
                    
                            <!-- Total Amount Highlight -->
                            <div class="row mb-0">
                                <div class="col-12">
                                    <div class="card bg-light text-dark shadow-sm">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0">Total Purchase Amount</h4>
                                            <h3 class="text-success fw-bold mb-0">
                                                {{ number_format($totalAmount, 2) }} TSH
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Item Table -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-white">
                                            <h5 class="card-title mb-0">Purchase Records</h5>
                                        </div>
                    
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped my-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name of Item</th>
                                                        <th>Department</th>
                                                        <th>Quantity</th>
                                                        <th>Expected Price</th>
                                                        <th>Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($item->count() > 0)
                                                        @foreach ($item as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $item->name }}</td>
                                                                <td>{{ $item->user->department }}</td>
                                                                <td>{{ $item->quantity }}</td>
                                                                <td>{{ number_format($item->price, 2) }}</td>
                                                                <td>{{ number_format($item->amount, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="6" class="text-center text-muted">No purchase records found</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    </main>
                    


                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Request</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('department.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="itemName" class="form-label">Item name</label>
                                        <input type="text" class="form-control" id="itemName" name="name"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="day" class="form-label">Department</label>
                                        <select class="form-select" id="day" name="department">
                                            <option value="" selected>Select category</option>
                                            <option value="Dep1">category 1</option>
                                            <option value="Dep2">category 2</option>

                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Expected price at each</label>
                                        <input type="number" step="0.01" class="form-control" id="price"
                                            name="price" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Reason</label>
                                        <textarea class="form-control" id="description" name="reason" rows="4" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success">Save</button>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </main>
    </div>


    <script src="{{ asset('static/js/app.js') }}"></script>

    <script src="js/app.js"></script>

</body>

</html>
