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



    <link rel="stylesheet"
        href="{{ asset('https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css') }}"
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

                            @if (auth()->user()->userType == 3)
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('general.index') }}">
                                        <span class="align-middle">Item purchase</span>
                                    </a>
                                </li>
                                <li class="sidebar-item active">
                                    <a class="sidebar-link" href="{{ url('/petty_first_approval') }}">
                                        <span class="align-middle">Last Approval</span>
                                    </a>
                                </li>
                            @endif

                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('petty.index') }}">
                                    <span class="align-middle">Petty Cash</span>
                                </a>
                            </li>

                            @if (auth()->user()->userType == 5 || auth()->user()->userType == 6)
                                <li class="sidebar-item active">
                                    <a class="sidebar-link" href="{{ url('/petty_first_approval') }}">
                                        <span class="align-middle">Approval</span>
                                    </a>
                                </li>
                            @endif

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

                            @if (auth()->user()->userType == 6)
                                <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" href="{{ url('/petty_first_approval') }}">Payment</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" href="{{ route('deposit.index') }}">Deposit</a>
                                    </li>
                                </ul>
                            @endif
                            <div class="mb-1" style="display: flex;justify-content: space-between;">
                                <h1 class="h3">Deposits </h1>

                                @if ($remaining < 100000)
                                    <div class="fs-5 bg-danger badge">Remaining Amount:
                                        <strong>{{ number_format($remaining, 0, '.', ',') }}/=</strong></div>
                                @else
                                    <div class="fs-5 bg-success badge">Remaining Amount:
                                        <strong>{{ number_format($remaining, 0, '.', ',') }}/=</strong></div>
                                @endif

                                <a class="btn btn-dark" data-bs-toggle="modal" href="#exampleModalToggle" role="button"
                                    data-bs-target="#staticBackdrop">
                                    Deposit
                                </a>
                            </div>



                            <div class="row">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif


                                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                                    <div class="card flex-fill">

                                        <div class="p-1" style="display: flex;justify-content: space-between;">
                                            <div class="num_rows">

                                                <div class="form-group"> <!--		Show Numbers Of Rows 		-->
                                                    <select class  ="form-control" name="state" id="maxRows">

                                                        <option value="10">10 rows</option>
                                                        <option value="15">15 rows</option>
                                                        <option value="20">20 rows</option>
                                                        <option value="50">50 rows</option>
                                                        <option value="70">70 rows</option>
                                                        <option value="100">100 rows </option>
                                                        <option value="5000">Show ALL Rows</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="position-relative tb_search" style="width: 30%;">
                                                <input type="text" id="search_input_all"
                                                    onkeyup="FilterkeyWord_all_table()" placeholder="Search.."
                                                    class="form-control  shadow-sm border-0"
                                                    placeholder="Search any data">
                                            </div>
                                        </div>

                                        <table class="table table-hover my-0" id= "table-id">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Deposit Amount</th>
                                                    <th>Remaing</th>
                                                    <th>Date deposited</th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTable">
                                                @if ($deposits->count() > 0)
                                                    @foreach ($deposits as $index => $deposit)
                                                        <tr>
                                                            <td>{{ $deposit->id }}</td>
                                                            <td class="d-none d-xl-table-cell">
                                                                {{ number_format($deposit->deposit, 0, '.', ',') }}/=
                                                            </td>
                                                            <td class="d-none d-xl-table-cell">
                                                                {{ number_format($deposit->remaining, 0, '.', ',') }}/=
                                                            </td>
                                                            <td class="d-none d-xl-table-cell">
                                                                {{ $deposit->created_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center">You have no request yet
                                                        </td>
                                                    </tr>
                                                @endif

                                            </tbody>
                                        </table>

                                        <div class="card-header d-sm-flex justify-content-between align-items-center">
                                            <div class='pagination-container'>
                                                <nav>
                                                    <ul class="pagination">
                                                        <!--	Here the JS Function Will Add the Rows -->
                                                    </ul>
                                                </nav>
                                            </div>
                                            <div class="rows_count">Showing 11 to 20 of 91 entries</div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </main>

                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" id="exampleModalToggle"
                        aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel">New Deposit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <form method="POST" action="{{ route('deposit.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">Amount of Money</label>
                                            <input type="number" name="deposit" class="form-control" required>
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                                    </div>

                                </form>


                            </div>
                        </div>
                    </div>


                    <script src="{{ asset('static/js/app.js') }}"></script>
                    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
