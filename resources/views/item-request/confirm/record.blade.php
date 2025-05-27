<x-app-layout>

                            <div class="row mb-0">
                                <div class="d-flex mb-4 col-6">
                                    <h1 class="h3 mb-0">Item Purchase Records
                                    </h1>
                                </div>

                                <div class="col-6 text-end">
                                    <!-- Print Button -->
                                    <button class="btn btn-primary" onclick="printTable()">Print</button>
                                </div>
                            </div>


                            <!-- Total Amount Highlight -->
                            <div class="row mb-0">
                                <div class="col-8">
                                    <form method="GET" action="{{ route('items.filter') }}" class="d-flex">
                                        <div class="form-group me-2">
                                            <label for="start_date">Start Date</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                value="{{ request('start_date') }}">
                                        </div>
                                        <div class="form-group me-2">
                                            <label for="end_date">End Date</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                value="{{ request('end_date') }}">
                                        </div>
                                        <div class="form-group me-2">
                                            <label for="p_type">Purchase Type</label>
                                            <select name="p_type" id="p_type" class="form-control">
                                                <option value="All"
                                                    {{ request('p_type') == 'All' ? 'selected' : '' }}>All</option>
                                                <option value="Minor"
                                                    {{ request('p_type') == 'Minor' ? 'selected' : '' }}>Minor</option>
                                                <option value="Major"
                                                    {{ request('p_type') == 'Major' ? 'selected' : '' }}>Major</option>
                                            </select>
                                        </div>
                                        <div class="form-group me-2 align-self-end">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-4">
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
                                            <table class="table table-hover table-border my-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Name of Item</th>
                                                        <th>Department</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                        <th>Total Amount (Tsh)</th>
                                                        <th>Purchase type</th>
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
                                                                @if ($item->p_type == 'Minor')
                                                                    <td> <span
                                                                            class="badge bg-success ms-2 text-sm ms-2">{{ $item->p_type }}</span>
                                                                    </td>
                                                                @else
                                                                    <td> <span
                                                                            class="badge bg-danger ms-2 text-sm ms-2">{{ $item->p_type }}</span>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted">No
                                                                purchase records found</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>




    <script>
        function printTable() {
            // Get the values for p_type, start_date, end_date, and totalAmount
            var pType = document.getElementById('p_type').value || 'All';
            var startDate = document.getElementById('start_date').value || 'N/A';
            var endDate = document.getElementById('end_date').value || 'N/A';
            var totalAmount = '{{ number_format($totalAmount, 2) }}';

            // Create the header with logo, details, and a double line
            var printHeader = `
                <div style="text-align: center;">
                    <img src="{{ asset('image/longl.png') }}" alt="Company Logo" style="width: 150px; height: 150px; display: block; margin: 0 auto;" />
                    <hr style="border: double; margin: 20px 0;">
                      <h1 style="font-size: 20px; font-weight: bold;"> MARS COMMUNICATION LIMITED</h1>
                    <p>Address: 123 Samora St, Dar es salaam, Tanzania</p>
                    <p>Email: info@marstanzania.com | Phone: +255 748 569 700</p>
                     <hr style="margin: 10px 0;">
                    <h1 style="margin-bottom: 0; font-weight: bold;">ITEM PURCHASE REQUEST</h1>
                    <h4>${pType} Purchases from ${startDate} to ${endDate}</h4>

 <hr style="margin: 10px 0;">
                </div>
            `;

            // Footer with GM signature and stamp placeholders at the bottom of the page
            var printFooter = `
                <footer style="position: absolute; bottom: 50px; width: 100%; display: flex; justify-content: space-between; padding: 0 50px;">
                    <div style="text-align: left;">
                        <p>GM Signature:</p>
                         <hr style="margin: 15px 0;">
                        <p>________________________</p>
                    </div>
                    <div style="text-align: center; margin-top: 60px;">
                    <p>mars communication ltd</p>
                </div>
                    <div style="text-align: right;">
                        <p>Stamp:</p>
                        <hr style="margin: 15px 0;">
                        <p>________________________</p>
                    </div>
                </footer>

            `;

            var table = document.querySelector('.table-responsive').innerHTML;
            var styledTable = `
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    table, th, td { border: 1px solid black; padding: 8px; text-align: left; }
                    th, td { border: 1px solid #000; }
                    table tr th:nth-child(7), table tr td:nth-child(7) { display: none; } /* Hide last column */
                </style>
                <table>
                    ${table}
                </table>
            `;

            var totalRow = `
                <tr>
                    <td colspan="5" style="text-align: right; font-weight: bold;">Total Purchase Amount</td>
                    <td style="font-weight: bold;">${totalAmount}</td>
                </tr>
            `;
            styledTable = styledTable.replace('</tbody>', `${totalRow}</tbody>`);

            // Save the original body content
            var originalContents = document.body.innerHTML;

            // Replace body content with the header, table, and footer for printing
            document.body.innerHTML = printHeader + styledTable + printFooter;

            // Trigger print
            window.print();

            // Restore original content after printing and refresh the page
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>


</x-app-layout>
