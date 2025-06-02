@extends('layouts.report')

@section('content')

<div class="text-center">
      <h2 class="text-center">MARS COMMUNICATIONS LTD <br> PETTY CASH REPLENISHMENT FOR {{ optional(Auth::user()->department)->name ?? 'N/A' }}
    @if (request()->filled('from') && request()->filled('to'))
        FROM {{ request('from') }} TO {{ request('to') }}
    @endif</h2>
</div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
               <th>Request For</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($petties as $i => $petty)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $petty->user->name }}</td>
                    <td>{{ $petty->request_for }}</td>
                    <td>{{ $petty->amount }}</td>
                    <td>{{ $petty->created_at->format('d M Y') }}</td>
                    <td>{{ $petty->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
