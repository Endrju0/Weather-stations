@extends('layouts.main')

@section('title', 'Weather Stations - Settings ' . $user->name)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>

<style>
    #mapid { height: 300px; }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Profile edit</div>
            <form method="POST" action="{{ route('settings.update') }}">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="control-label">Name</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="timezone" class="control-label">Timezone</label>
                        <select id="timezone" name="timezone" class="form-control">
                            @foreach ($timezones as $timezone)
                                <option value="{{ $timezone }}" @if($user->timezone == null) {{ $timezone == config('app.timezone')  ? 'selected' : '' }} @else {{ $timezone == $user->timezone ? 'selected' : '' }} @endif>{{ $timezone }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row pt-2">
                        <div class="col-12">Map center</div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude" class="control-label">Latitude</label>
                                <input id="latitude" type="text" class="form-control" name="latitude" value="{{ $user->center_latlng[0] }}" @if($user->center_latlng[0] == null) placeholder="{{ config('leaflet.center_latitude') }}" @endif>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude" class="control-label">Longitude</label>
                                <input id="longitude" type="text" class="form-control" name="longitude" value="{{ $user->center_latlng[1] }}" @if($user->center_latlng[1] == null) placeholder="{{ config('leaflet.center_longitude') }}" @endif>
                            </div>
                        </div>
                        <input name="url" type="hidden" value="{{ url()->previous() }}">
                    </div>
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="Update" class="btn btn-primary">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
    integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
    crossorigin=""></script>

@if (!empty($center))
<script>
    var mapCenter = @json($center);
</script>
@else
<script>
    var mapCenter = [{{ config('leaflet.center_latitude') }}, {{ config('leaflet.center_longitude') }}];
</script>
@endif

<script>
    var map = L.map('mapid').setView(mapCenter, {{ config('leaflet.zoom') }});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    }).addTo(map);

    var marker = L.marker(mapCenter).addTo(map);
    function updateMarker(lat, lng) {
        marker
        .setLatLng([lat, lng])
        .bindPopup("Your location :  " + marker.getLatLng().lat.toString() + ' : ' + marker.getLatLng().lng.toString())
        .openPopup();
        return false;
    };

    map.on('click', function(e) {
        let latitude = e.latlng.lat.toString().substring(0, 15);
        let longitude = e.latlng.lng.toString().substring(0, 15);
        $('#latitude').val(latitude);
        $('#longitude').val(longitude);
        updateMarker(latitude, longitude);
    });

    var updateMarkerByInputs = function() {
        return updateMarker( $('#latitude').val() , $('#longitude').val());
    }
    $('#latitude').on('input', updateMarkerByInputs);
    $('#longitude').on('input', updateMarkerByInputs);
</script>
@endpush