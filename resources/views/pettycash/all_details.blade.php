

@extends('layouts.petty_app')

@section('content')

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

                            @if(auth()->user()->userType == 5 || auth()->user()->userType == 6)
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ url('/petty_first_approval') }}">
                                    <span class="align-middle">Approval</span>
                                </a>
                            </li>
                            @endif

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

                                <div class="col-12 col-lg-12 col-xxl-12 d-flex mt-2">

                                        <div class="card shadow-sm col-12 border-0">

                                            <div class="card-body">

                                                <div class="mb-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p class="fs-4"><strong>Petty cash for:</strong>
                                                                {{ $request->name }}</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Petty cash type:</strong>
                                                                {{ $request->request_type }}</p>
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

                                                    @if ($request->request_type == 'Petty Cash')
                                                        <h5 class="text-secondary mb-3 text-primary"><strong>List of
                                                                Items ,Service or Routes</strong></h5>

                                                        @if ($request->pettyLists->count() > 0)
                                                            <ul class="list-group">
                                                                @foreach ($request->pettyLists as $item)
                                                                    <li
                                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                                        {{ $item->item_name }}
                                                                        <span class="badge bg-danger rounded-pill"><i
                                                                                class="fa fa-check"></i></span>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <p class="text-muted">No items associated with this request.
                                                            </p>
                                                        @endif
                                                    @else
                                                    <h5 class="text-secondary mb-3 text-primary">
                                                        <strong>Attachment</strong>
                                                        <!-- Download Icon -->
                                                        <a href="{{ asset($request->attachment) }}" download class="badge bg-primary text-decoration-none ms-2">
                                               download
                                                        </a>
                                                    </h5>

                                                    <!-- Thumbnail Image -->
                                                    <img src="{{ asset($request->attachment) }}" alt="Loading ..." style="height: 200px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal">

                                                    <!-- Full-Screen Image Modal -->
                                                    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="imageModalLabel">Attachment Image</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <img src="{{ asset($request->attachment) }}" alt="Loading ..." class="img-fluid">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @endif
                                                </div>
                                                @if (!empty($request->comment))
                                                <div class="mb-4">
                                                    <h5 class="text-danger mb-3 text-primary"><strong>Reason for
                                                            rejection</strong></h5>
                                                    {{ $request->comment }}
                                                </div>
                                            @endif
                                                @if ($request->status == 'paid')
                                                <div class="text-end">
                                                   Paid: <div class="btn btn-success">TZS
                                                        {{ number_format($request->amount, 2) }}/=</div>
                                                </div>
                                                @else
                                                <div class="text-end">
                                                    Amount Needed: <div class="btn btn-secondary">TZS
                                                        {{ number_format($request->amount, 2) }}/=</div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                </div>

                            </div>

                        </div>
                    </main>



  @endsection
