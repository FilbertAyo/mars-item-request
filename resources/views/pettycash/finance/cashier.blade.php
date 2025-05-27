<x-app-layout>

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

</x-app-layout>
