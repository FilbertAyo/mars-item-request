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
                                <a class="sidebar-link" href="{{ route('branch.index') }}">
                                    <span class="align-middle">Item purchase</span>
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
                        <div class="container-fluid p-0">

                            <div class="mb-1" style="display: flex;justify-content: space-between;">


                                <h1 class="h3 mb-3">Request details
                                    @if ($item->status == 'rejected')
                                    <span class="badge bg-danger text-white text-sm ms-2">
                                        You {{ $item->status }} this request
                                    </span>
                                @elseif($item->status == 'processing')
                                    <span class="badge bg-success text-white text-sm ms-2">
                                        You approve this request
                                    </span>
                                @endif
                                </h1>


                                @if ($item->status == 'pending')
                                    <a class="btn btn-dark" data-bs-toggle="modal" href="#exampleModalToggle"
                                        role="button" data-bs-target="#staticBackdrop">
                                        Check
                                    </a>
                                @endif
                            </div>

                            <div class="row">

                                <div class="col-6 col-md-6 col-xxl-6 d-flex order-2 order-xxl-6">
                                    <div class="card flex-fill w-100">
                                        <div class="card-header">

                                            <h5 class="card-title mb-0">Item purchase request details</h5>
                                        </div>
                                        <div class="card-body d-flex">
                                            <div class="align-self-center w-100">


                                                <table class="table mb-0">
                                                    <tbody>

                                                        <tr>
                                                            <td>Name of item</td>
                                                            <td class="text-end">{{ $item->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Quantity required</td>
                                                            <td class="text-end">{{ $item->quantity }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Price at each item</td>
                                                            <td class="text-end">{{ $item->price }}/=</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total amount</td>
                                                            <td class="text-end text-success">{{ $item->amount }}/=
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>department</td>
                                                            <td class="text-end text-warning">
                                                                {{ $item->user->department }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Time of request</td>
                                                            <td class="text-end">{{ $item->created_at }}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-6 col-md-6 col-xxl-6 d-flex order-2 order-xxl-6">
                                    <div class="card flex-fill w-100">
                                        <div class="card-header">

                                            <h5 class="card-title mb-0">Reasons for the request</h5>
                                        </div>
                                        <div class="card-body d-flex">
                                            {{ $item->reason }}
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="row">
                            @if($item->branch_comment != 'no comment')
                            <div class="col-6 col-md-6 col-xxl-6 d-flex order-xxl-6">
                                <div class="card flex-fill w-100">
                                    <div class="card-header">

                                        <h5 class="card-title mb-0">Branch manager comment</h5>
                                    </div>
                                    <div class="card-body d-flex">
                                        {{ $item->branch_comment }}
                                    </div>

                                </div>

                            </div>
                            @endif

                            @if($item->gm_comment != 'no comment')
                            <div class="col-6 col-md-6 col-xxl-6 d-flex order-xxl-6">
                                <div class="card flex-fill w-100">
                                    <div class="card-header">

                                        <h5 class="card-title mb-0">General manager comment</h5>
                                    </div>
                                    <div class="card-body d-flex">
                                        {{ $item->gm_comment }}
                                    </div>

                                </div>

                            </div>
                            @endif

                        </div>

                            <div class="d-grid">

                                @if ($item->status == 'pending')
                                    <span class="btn btn-info">{{ $item->status }}</span>
                                @elseif($item->status == 'processing')
                                    <span class="btn btn-warning">{{ $item->status }}</span>
                                @elseif($item->status == 'rejected')
                                    <span class="btn btn-danger">{{ $item->status }}</span>
                                @else
                                    <span class="btn btn-success">{{ $item->status }}</span>
                                @endif

                            </div>


                        </div>
                    </main>


                </div>


                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" id="exampleModalToggle"
                    aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Purchase approval</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                After carefully reviewing the provided details of {{ $item->name }}, please choose
                                whether to
                                approve or reject. Your decision will help us proceed with the appropriate action. <br>
                                Do you approve or reject this item?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-bs-target="#exampleModalToggle2"
                                    data-bs-toggle="modal" data-bs-dismiss="modal">Reject</button>
                                <a href="{{ route('item.approve', ['id' => $item->id]) }}"
                                    class="btn btn-success">Approve</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModalToggle2" aria-hidden="true"
                    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="exampleModalToggleLabel2"> Reason for
                                    rejection</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('item.reject', ['id' => $item->id]) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <textarea class="form-control" id="description" name="branch_comment" rows="4" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success"
                                        data-bs-dismiss="modal">Submit</button>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script src="js/app.js"></script>
    <script src="{{ asset('static/js/app.js') }}"></script>

</body>

</html>
