<x-app-layout>

                            <div class="mb-1" style="display: flex;justify-content: space-between;">


                                <h1 class="h3 mb-3">Request details
                                    @if ($item->status == 'rejected')
                                    <span class="badge bg-danger text-white text-sm ms-2">
                                        You {{ $item->status }} this request
                                    </span>
                                @elseif($item->status == 'processing')
                                    <span class="badge bg-success text-white text-sm ms-2">
                                        You approve this request
                                    </span>
                                @endif
                                </h1>


                                @if ($item->status == 'pending')
                                    <a class="btn btn-dark" data-bs-toggle="modal" href="#exampleModalToggle"
                                        role="button" data-bs-target="#staticBackdrop">
                                        Check
                                    </a>
                                @endif
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
                                                            <td class="text-end text-success">{{ $item->amount }}/=
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>department</td>
                                                            <td class="text-end text-warning">
                                                                {{ $item->user->department }}</td>
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
                            @if($item->branch_comment != 'no comment')
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

                            @if($item->gm_comment != 'no comment')
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





                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" id="exampleModalToggle"
                    aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Purchase approval</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                After carefully reviewing the provided details of {{ $item->name }}, please choose
                                whether to
                                approve or reject. Your decision will help us proceed with the appropriate action. <br>
                                Do you approve or reject this item?
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-bs-target="#exampleModalToggle2"
                                    data-bs-toggle="modal" data-bs-dismiss="modal">Reject</button>
                                <a href="{{ route('item.approve', ['id' => $item->id]) }}"
                                    class="btn btn-success">Approve</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModalToggle2" aria-hidden="true"
                    aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-danger" id="exampleModalToggleLabel2"> Reason for
                                    rejection</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('item.reject', ['id' => $item->id]) }}">
                                    @csrf

                                    <div class="mb-3">
                                        <textarea class="form-control" id="description" name="branch_comment" rows="4" required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success"
                                        data-bs-dismiss="modal">Submit</button>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </x-app-layout>
