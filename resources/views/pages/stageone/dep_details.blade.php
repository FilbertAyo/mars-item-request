<x-app-layout>





    <div class="main">
        @include('layouts.navigation')

        <main class="content">
            <div class="container-fluid p-0">

                <div class="mb-1" style="display: flex;justify-content: space-between;">
                    <h1 class="h3 mb-3">Request details
                        @if ($item->status == 'rejected')
                            <span class="badge bg-danger text-white text-sm ms-2">
                                Your request is {{ $item->status }}
                            </span>
                        @elseif($item->status == 'approved')
                            <span class="badge bg-success text-white text-sm ms-2">
                                Your request is {{ $item->status }}
                            </span>
                        @endif
                    </h1>
                    <a href="{{ route('department.index') }}" class="btn btn-dark">
                        Back
                    </a>
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
                                                <td class="text-end text-success">{{ $item->amount }}/=</td>
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
                    @if ($item->branch_comment != 'no comment')
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

                    @if ($item->gm_comment != 'no comment')
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


    <script src="js/app.js"></script>





</x-app-layout>
