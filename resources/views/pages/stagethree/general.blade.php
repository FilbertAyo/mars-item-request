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
                            @if(auth()->user()->userType == 3)
                            <li class="sidebar-item active">
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
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('petty.index') }}">
                                    <span class="align-middle">Petty Cash</span>
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

                                <h1 class="h3 mb-3">Requests <a class="badge bg-primary text-white text-sm ms-2">
                                        {{ Auth::user()->department }}
                                    </a></h1>

                                    <a class="btn btn-primary" href="{{ url('/record') }}">
                                        Records
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
                                                    <th>Department</th>
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

                                                            <td class="d-none d-xl-table-cell">{{ $item->name }}</td>
                                                            <td class="d-none d-xl-table-cell">
                                                                {{ $item->user->department }}
                                                            </td>
                                                            <td class="d-none d-xl-table-cell">{{ $item->quantity }}
                                                            </td>
                                                            <td class="d-none d-xl-table-cell">{{ $item->price }}</td>
                                                            <td class="d-none d-xl-table-cell">{{ $item->amount }}</td>
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
                                                                <a href="{{ route('general.show', $item->id) }}"
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


                </div>
            </div>
        </main>
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

    <script src="{{ asset('static/js/app.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
