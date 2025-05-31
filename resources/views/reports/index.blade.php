<x-app-layout>

    <a href="{{ route('reports.users') }}">Users List</a>

    @can('view cashflow movements')
    <a href="{{ route('reports.petties') }}">Petty Cash Report</a>
    @endcan

</x-app-layout>
