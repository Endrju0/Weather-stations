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
                    <input placeholder="Station Name" name="name" type="text" class="form-control mr-2" value="@if($name != null){{ $name }}@endif">
                    <div class="custom-control custom-checkbox mx-2">
                        <input type="checkbox" class="custom-control-input" id="self" name="self" @if($self != null) checked @endif>
                        <label class="custom-control-label" for="self">Show only mine</label>
                    </div>
                    <input type="submit" value="Search" class="btn btn-outline-primary">
                    <a href="{{ route('station-list.index') }}" class="btn btn-link">Reset</a>
                </form>
                <a href="{{ route('station.create') }}" class="btn btn-primary ml-auto mr-2">Create new station</a>
            </div>
        </div>
        <div class="table-responsive">
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
                    @forelse($stations as $key => $station)    
                    <tr>
                        <th scope="row">{{ ++$key }}</th>
                        <td>{{ $station->name }}</td>
                        <td>{{ $station->latitude }}</td>
                        <td>{{ $station->longitude }}</td>
                        <td><a href="{{ route('station.show', $station->id) }}" class="btn btn-sm btn-secondary">Show</a></td>
                    </tr>
                    @empty
                        <td colspan="5">No stations available.</td>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center col-12">{{ $stations->links() }}</div>
    </div>
@endsection

@push('scripts')

@endpush