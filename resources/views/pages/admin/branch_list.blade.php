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

          <li class="sidebar-item ">
              <a class="sidebar-link" href="{{ route('admin.index') }}">
                  <span class="align-middle">Users</span>
              </a>
          </li>

         <li class="sidebar-item active">
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
                    <h1 class="h3 mb-3"> Branches List <a class="badge bg-primary text-white text-sm ms-2">
                        {{ Auth::user()->department }}
                    </a></h1> <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Add Branch
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

                                <h5 class="card-title mb-0">Branches</h5>
                            </div>
                            <table class="table table-hover my-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($branch->count() > 0)
                                        @foreach ($branch as $index => $branch)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $branch->name }}</td>

                                                <td>
                                                    <form action="{{ url('/destroy_branch/' . $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                    </form>

                                                    </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center">No Branch found</td>
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
                    <h5 class="modal-title" id="exampleModalLabel">New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('/add_branch') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="topic" class="form-label">Branch name</label>
                            <input type="text" class="form-control" id="topic" name="name" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>


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
