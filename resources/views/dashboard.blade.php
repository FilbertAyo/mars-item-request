<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Mars communications | Purchase request</title>

        {{-- added --}}
        <link rel="canonical" href="{{ asset('https://v5.getbootstrap.com/docs/5.0/examples/dashboard/') }}">

    <link rel="preconnect" href="{{ asset('https://fonts.gstatic.com') }}">
    <link rel="shortcut icon" href="{{ asset('static/img/icons/icon-48x48.png') }}" />
    <link rel="canonical" href="{{ asset('https://demo-basic.adminkit.io/') }}" />
    <link href="{{ asset('static/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">



  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
    integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
    integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/"
    crossorigin="anonymous"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="{{ asset('https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap') }}" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


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
                    <img src="/image/logo.png" alt="" class="img-fluid align-middle" style="max-height: 50px;">
        </a>

        <ul class="sidebar-nav">
          <li class="sidebar-header">Pages</li>

          <li class="sidebar-item active">
              <a class="sidebar-link" href="{{ route('admin.index') }}">
                  <span class="align-middle">Users</span>
              </a>
          </li>

         <li class="sidebar-item">
              <a class="sidebar-link" href="{{ url('/branch_list') }}">
                 <span class="align-middle">Branch</span>
              </a>
          </li>
      </ul>



					<div class="sidebar-cta-content">
						<strong class="d-inline-block mb-2">MC2024</strong>

						<div class="d-grid">
							<div class="btn btn-primary"></div>
						</div>
					</div>

			</div>
		</nav>
    <div class="main">
        @include('layouts.navigation')

        <main class="content">
            <div class="container-fluid">

                <div class="mb-1" style="display: flex;justify-content: space-between;">
                    <h1 class="h3 mb-3"> Users <a class="badge bg-primary text-white text-sm ms-2">
                        {{ Auth::user()->department }}
                    </a></h1>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Add User
                    </button>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">

                                <h5 class="card-title mb-0">User registration</h5>
                            </div>
                            <table class="table table-hover my-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone number</th>
                                        <th>Department | Management</th>
                                        <th>Branch</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($user->count() > 0)
                                        @foreach ($user as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $user->name }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->department }}</td>
                                                <td>{{ $user->branch }}</td>
                                                <td>
                                                    {{-- <form action="{{ route('admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this agenda?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                    </form> --}}

                                                    <span class="badge bg-success">{{ $user->status }}</span>
                                                    <a href="{{ route('admin.show',$user->id) }}" class="badge bg-primary text-white">view</a>


                                                    </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No user found</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
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
                    <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="topic" class="form-label">Full name</label>
                            <input type="text" class="form-control" id="topic" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="topicType" class="form-label">Email</label>
                            <input type="text" class="form-control" id="topicType" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="topicType" class="form-label">Phone number</label>
                            <input type="text" class="form-control" id="topicType" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="branch" class="form-label">Branch</label>
                            <select class="form-select" id="branch" name="branch">
                                <option value="" selected>Select branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="day" class="form-label">Role | Department</label>
                            <select class="form-select" id="day" name="department">

                                <option value="" selected>--Select Role --</option>
                                <option value="Petty Cash">Petty Cash</option>
                                <option value="All department">All department</option>
                                <option value="IT department">IT department</option>
                                <option value="Sales department">Sales department</option>
                                <option value="Account and Finance">Account and Finance</option>
                                <option value="Branch manager">Branch Manager</option>
                                <option value="General manager">General Manager</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="dateTime" class="form-label">Approval stage</label>
                            <input type="text" class="form-control" id="userType" name="userType" readonly>
                        </div>
                    </div>
                        <button type="submit" class="btn btn-primary">Save</button>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const daySelect = document.getElementById('day');
                                const dateTimeInput = document.getElementById('userType');

                                const userType = {
                                    'IT department': '1',
                                    'Sales department': '1',
                                    'Branch manager': '2',
                                    'General manager': '3',
                                    'Petty Cash': '4',
                                };

                                daySelect.addEventListener('change', function() {
                                    const selectedDay = daySelect.value;
                                    dateTimeInput.value = userType[selectedDay] || '';
                                });
                            });
                        </script>
                    </form>

                </div>

            </div>
        </div>
    </div>

</div>
</main>
</div>


<script src="{{ asset('static/js/app.js') }}"></script>

</body>
</html>
