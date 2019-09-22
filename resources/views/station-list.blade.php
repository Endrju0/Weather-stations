@extends('layouts.main')

@section('styles')

<style>
    table {
        width: 100%;
    }
</style>

@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <form method="GET" action="" class="form-inline ml-2">
                    <input placeholder="Station Name" name="query" type="text" class="form-control mr-2" value="{{ request('query') }}">
                    <input type="submit" value="Search" class="btn btn-outline-primary">
                    <a href="{{ route('station-list.index') }}" class="btn btn-link">Reset</a>
                </form>
                <a href="{{ route('station.create') }}" class="btn btn-primary ml-auto mr-2">Create new station</a>
            </div>
        </div>
            <table class="table table-sm text-center">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Latitude</th>
                        <th scope="col">Longitude</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stations as $key => $station)    
                    <tr>
                        <th scope="row">{{ ++$key }}</th>
                        <td>{{ $station->name }}</td>
                        <td>{{ $station->latitude }}</td>
                        <td>{{ $station->longitude }}</td>
                        <td><a href="{{ route('station.show', $key) }}" class="btn btn-sm btn-secondary">Show</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center col-12">{{ $stations->links() }}</div>
    </div>
@endsection

@push('scripts')

@endpush