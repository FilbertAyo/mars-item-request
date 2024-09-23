<x-app-layout>



    <div class="main">
        @include('layouts.navigation')

        <main class="content">
            <div class="container-fluid p-0">

                <div class="mb-1" style="display: flex;justify-content: space-between;">
                    <h1 class="h3 mb-3">Requests<a class="badge bg-primary text-white text-sm ms-2">
                            {{ Auth::user()->department }}
                        </a></h1> <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Add Request
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
                                            <option value="100">100 rows    </option>
                                            <option value="5000">Show ALL Rows</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="position-relative tb_search" style="width: 30%;">
                                    <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()"
                                        placeholder="Search.." class="form-control  shadow-sm border-0"
                                        placeholder="Search any data">
                                </div>

                            </div>

                            <table class="table table-hover my-0" id= "table-id">
                                <thead class="table-dark">
                                    <tr>
                                     
                                        <th>Name of item</th>
                                        {{-- <th>Category</th> --}}
                                        <th>Quantity</th>
                                        <th>Expected price</th>
                                        <th>Total amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    @if ($item->count() > 0)
                                        @foreach ($item as $index => $item)
                                            <tr>
                                                {{-- <td>{{ $index + 1 }}</td> --}}
                                                <td class="d-none d-xl-table-cell">{{ $item->name }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->quantity }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->price }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->amount }}</td>
                                                <td>
                                                    {{-- <form action="{{ route('visitor.destroy', $visitor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this visitor?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                    </form> --}}
                                                    @if ($item->status == 'pending')
                                                        <span class="badge bg-info">{{ $item->status }}</span>
                                                    @elseif($item->status == 'processing')
                                                        <span class="badge bg-warning">{{ $item->status }}</span>
                                                    @elseif($item->status == 'rejected')
                                                        <span class="badge bg-danger">{{ $item->status }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ $item->status }}</span>
                                                    @endif
                                                    <a href="{{ route('department.show', $item->id) }}"
                                                        class="badge bg-primary text-white">view</a>


                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">You have no request yet</td>
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



    </div>


    <script src="js/app.js"></script>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('department.store') }}">
                        @csrf

                        <input type="text" class="form-control" id="itemName" name="branch" value="{{ Auth::user()->branch }} " style="display: none">

                        <div class="mb-3">
                            <label for="itemName" class="form-label">Item name</label>
                            <input type="text" class="form-control" id="itemName" name="name" required>
                        </div>
                      
                        <div class="row mb-3">
                        <div class="col-6">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="col-6">
                            <label for="price" class="form-label">Expected price at each</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="day" class="form-label">Justification</label>
                        <select class="form-select" id="day" name="justification">
                            <option value="" selected>Select justificaton</option>
                            <option value="Replacement">Replacement</option>
                            <option value="New employee">New employee</option>
                            <option value="Damage">Damage</option>
                            <option value="Other">Other .. </option>
                        </select>
                    </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Reason</label>
                            <textarea class="form-control" id="description" name="reason" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>

                </div>

            </div>
        </div>
    </div>


    <script>
        getPagination('#table-id');
        $('#maxRows').trigger('change');

        function getPagination(table) {

            $('#maxRows').on('change', function() {
                $('.pagination').html(''); // reset pagination div
                var trnum = 0; // reset tr counter
                var maxRows = parseInt($(this).val()); // get Max Rows from select option

                var totalRows = $(table + ' tbody tr').length; // numbers of rows
                $(table + ' tr:gt(0)').each(function() { // each TR in  table and not the header
                    trnum++; // Start Counter
                    if (trnum > maxRows) { // if tr number gt maxRows

                        $(this).hide(); // fade it out
                    }
                    if (trnum <= maxRows) {
                        $(this).show();
                    } // else fade in Important in case if it ..
                }); //  was fade out to fade it in
                if (totalRows > maxRows) { // if tr total rows gt max rows option
                    var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
                    //	numbers of pages
                    for (var i = 1; i <= pagenum;) {
                        $('.pagination').append('<li data-page="' + i + '">\
        <div class="card text-center" style="width: 70px; margin: 2px;">\
          <div class="card-body p-0">\
            <span class="page-link">' + i++ + '</span>\
          </div>\
        </div>\
      </li>').show();
                    }
                    // end for i


                } // end if row count > max rows
                $('.pagination li:first-child').addClass('active'); // add active class to the first li

                //SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT
                showig_rows_count(maxRows, 1, totalRows);
                //SHOWING ROWS NUMBER OUT OF TOTAL DEFAULT

                $('.pagination li').on('click', function(e) { // on click each page
                    e.preventDefault();
                    var pageNum = $(this).attr('data-page'); // get it's number
                    var trIndex = 0; // reset tr counter
                    $('.pagination li').removeClass('active'); // remove active class from all li
                    $(this).addClass('active'); // add active class to the clicked

                    //SHOWING ROWS NUMBER OUT OF TOTAL
                    showig_rows_count(maxRows, pageNum, totalRows);
                    //SHOWING ROWS NUMBER OUT OF TOTAL

                    $(table + ' tr:gt(0)').each(function() { // each tr in table not the header
                        trIndex++; // tr index counter
                        // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
                        if (trIndex > (maxRows * pageNum) || trIndex <= ((maxRows * pageNum) -
                                maxRows)) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        } //else fade in
                    }); // end of for each tr in table
                }); // end of on click pagination list
            });
        }

        // SI SETTING
        $(function() {
            // Just to append id number for each row
            default_index();
        });

        //ROWS SHOWING FUNCTION
        function showig_rows_count(maxRows, pageNum, totalRows) {
            //Default rows showing
            var end_index = maxRows * pageNum;
            var start_index = ((maxRows * pageNum) - maxRows) + parseFloat(1);
            var string = 'Showing ' + start_index + ' to ' + end_index + ' of ' + totalRows + ' entries';
            $('.rows_count').html(string);
        }

        // CREATING INDEX
        function default_index() {
            $('table tr:eq(0)').prepend('<th> ID </th>')

            var id = 0;

            $('table tr:gt(0)').each(function() {
                id++
                $(this).prepend('<td>' + id + '</td>');
            });
        }

        // All Table search script
        function FilterkeyWord_all_table() {

            // Count td if you want to search on all table instead of specific column

            var count = $('.table').children('tbody').children('tr:first-child').children('td').length;

            // Declare variables
            var input, filter, table, tr, td, i;
            input = document.getElementById("search_input_all");
            var input_value = document.getElementById("search_input_all").value;
            filter = input.value.toLowerCase();
            if (input_value != '') {
                table = document.getElementById("table-id");
                tr = table.getElementsByTagName("tr");

                // Loop through all table rows, and hide those who don't match the search query
                for (i = 1; i < tr.length; i++) {

                    var flag = 0;

                    for (j = 0; j < count; j++) {
                        td = tr[i].getElementsByTagName("td")[j];
                        if (td) {

                            var td_text = td.innerHTML;
                            if (td.innerHTML.toLowerCase().indexOf(filter) > -1) {
                                //var td_text = td.innerHTML;
                                //td.innerHTML = 'shaban';
                                flag = 1;
                            } else {
                                //DO NOTHING
                            }
                        }
                    }
                    if (flag == 1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            } else {
                //RESET TABLE
                $('#maxRows').trigger('change');
            }
        }
    </script>

</x-app-layout>
