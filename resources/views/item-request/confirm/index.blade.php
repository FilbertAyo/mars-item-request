<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Item Requests</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-house-fill"></i>
                </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Item Request</a>
            </li>
            
        </ul>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3"> Requests</h4>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name of item</th>
                                    <th>Branch</th>
                                    <th>Quantity</th>
                                    <th>Total amount</th>
                                    <th>Type</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                     <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Name of item</th>
                                    <th>Branch</th>
                                    <th>Quantity</th>
                                    <th>Total amount</th>
                                    <th>Type</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                     <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($item as $index => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            {{ $item->branch->name }}
                                        </td>
                                        <td>{{ $item->quantity }}
                                        </td>
                                        <td>{{ $item->amount }}</td>
                                         <td>{{ $item->p_type }}</td>
                                         <td>{{ $item->user->name }}</td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <span class="badge bg-danger">{{ $item->status }}</span>
                                            @elseif($item->status == 'processing')
                                                <span class="badge bg-warning">{{ $item->status }}</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-secondary">{{ $item->status }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>

                                           <a href="{{ route('item-request.details', $item->id) }}"
                                                class="btn btn-sm btn-secondary text-white"><i
                                                    class="bi bi-eye"></i></a>
                                           
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
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
