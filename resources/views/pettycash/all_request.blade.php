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
                            @if(auth()->user()->userType == 3)
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('general.index') }}">
                                    <span class="align-middle">Item purchase</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ url('/petty_first_approval') }}">
                                    <span class="align-middle">Last Approval</span>
                                </a>
                            </li>
                            @endif
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

                                <h1 class="h3 mb-3">My Requests <a class="badge bg-primary text-white text-sm ms-2">
                                        {{ Auth::user()->department }}
                                    </a></h1>

                                <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Request
                                </button>
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
                                                    <th>Request for</th>
                                                    <th>Petty cash type</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="myTable">
                                                @if ($requests->count() > 0)
                                                    @foreach ($requests as $index => $item)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td class="d-none d-xl-table-cell">{{ $item->name }}</td>
                                                            <td class="d-none d-xl-table-cell">{{ $item->request_type }}
                                                            <td class="d-none d-xl-table-cell">{{ $item->amount }}

                                                            <td>

                                                                @if ($item->status == 'pending')
                                                                    <span
                                                                        class="badge bg-info">{{ $item->status }}</span>
                                                                @elseif($item->status == 'processing')
                                                                    <span
                                                                        class="badge bg-warning">{{ $item->status }}</span>
                                                                @elseif($item->status == 'rejected')
                                                                    <span
                                                                        class="badge bg-danger">{{ $item->status }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-success">{{ $item->status }}</span>
                                                                @endif
                                                                <a href="{{ route('petty.show', $item->id) }}"
                                                                    class="badge bg-primary text-white">view</a>
                                                            </td>
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
                                    <form method="POST" action="{{ route('petty.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group" style="display: none;">
                                            <input type="text" name="request_by"
                                                value="{{ Auth::user()->name }}" class="form-control" readonly>
                                            <input type="text" name="status" value="pending"
                                                class="form-control" readonly>
                                                <input type="text" name="user_id" value="{{ Auth::user()->id }}"
                                                class="form-control" readonly>
                                        </div>


                                        <!-- Petty Cash/Refund Selection -->
                                        <div class="form-group">
                                            <label for="request_type">Request Type:</label>
                                            <select name="request_type" id="request_type" class="form-control"
                                                required>
                                                <option value="Petty Cash">Petty Cash</option>
                                                <option value="Reimbursement">Reimbursement</option>
                                            </select>
                                        </div>
                                        <!-- Name Field -->
                                        <div class="form-group mt-3">
                                            <label for="name">Request for</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>

                                        <!-- Amount Field -->
                                        <div class="form-group mt-3">
                                            <label for="amount">Amount Needed:</label>
                                            <input type="number" step="0.01" name="amount" class="form-control"
                                                required>
                                        </div>

                                        <!-- Reason Field -->
                                        <div class="form-group mt-3">
                                            <label for="reason">Reason for Request:</label>
                                            <textarea name="reason" class="form-control" required></textarea>
                                        </div>

                                        <!-- Office Expense Item List (Visible by Default) -->
                                        <div class="form-group mt-3" id="office_expense_section">
                                            <label for="items">List of Items:</label>
                                            <div id="items_container">
                                                <div class="input-group mb-2">
                                                    <input type="text" name="items[]" class="form-control"
                                                        placeholder="Enter item name (e.g., Pen)">
                                                    <button type="button"
                                                        class="btn btn-danger btn-remove-item">Delete</button>
                                                </div>
                                            </div>
                                            <button type="button" id="add_item_btn"
                                                class="btn btn-secondary mt-2">Add Item</button>
                                        </div>

                                        <!-- Attach Receipt (Only visible for refund) -->
                                        <div class="form-group mt-3" id="attachment_section" style="display: none;">
                                            <label for="attachment">Attach Receipt (Optional):</label>
                                            <input type="file" name="attachment" class="form-control">
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="btn btn-primary mt-3">Save</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Show or hide sections based on request type (petty cash or refund)
                        document.getElementById('request_type').addEventListener('change', function() {
                            var requestType = this.value;

                            if (requestType === 'Petty Cash') {
                                document.getElementById('office_expense_section').style.display = 'block';
                                document.getElementById('attachment_section').style.display = 'none';
                            } else if (requestType === 'Reimbursement') {
                                document.getElementById('office_expense_section').style.display = 'none';
                                document.getElementById('attachment_section').style.display = 'block';
                            }
                        });

                        // Add new item input field with a delete button when the "Add Item" button is clicked
                        document.getElementById('add_item_btn').addEventListener('click', function() {
                            var itemContainer = document.createElement('div');
                            itemContainer.classList.add('input-group', 'mb-2');

                            var newItem = document.createElement('input');
                            newItem.type = 'text';
                            newItem.name = 'items[]';
                            newItem.classList.add('form-control');
                            newItem.placeholder = 'Enter item name (e.g., Pencil)';

                            var deleteButton = document.createElement('button');
                            deleteButton.type = 'button';
                            deleteButton.classList.add('btn', 'btn-danger', 'btn-remove-item');
                            deleteButton.textContent = 'Delete';

                            // Append the new item and delete button to the container
                            itemContainer.appendChild(newItem);
                            itemContainer.appendChild(deleteButton);

                            document.getElementById('items_container').appendChild(itemContainer);
                        });

                        // Event delegation to handle delete button clicks for each item
                        document.getElementById('items_container').addEventListener('click', function(event) {
                            if (event.target.classList.contains('btn-remove-item')) {
                                event.target.closest('.input-group').remove();
                            }
                        });
                    </script>



                </div>
            </div>
        </main>
    </div>


   @endsection
