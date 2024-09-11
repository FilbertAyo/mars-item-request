<x-app-layout>





    <div class="main">
        @include('layouts.navigation')

        <main class="content">
            <div class="container-fluid p-0">

                <div class="mb-1" style="display: flex;justify-content: space-between;">
                    <h1 class="h3 mb-3">Request details</h1>
                    <a href="{{ route('branch.index') }}" class="btn btn-dark">
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
                                <div class="align-self-center w-100">


                                    <table class="table mb-0">
                                        <tbody>
                                            <tr>
                                                {{-- <td> --}}
                                                {{ $item->reason }}
                                                {{-- </td> --}}
                                            </tr>

                                        </tbody>
                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                <div class="d-grid">
                    <span class="btn btn-danger">{{ $item->status }}</span>
                </div>


            </div>
        </main>


    </div>


    <script src="js/app.js"></script>





</x-app-layout>
