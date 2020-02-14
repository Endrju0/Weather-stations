@extends('layouts.main')

@section('title', 'Weather Stations - Edit ' . $station->name)

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
            <div class="card-header">Edit station</div>
            <form method="POST" action="{{ route('station.update', $station) }}">
            {{ csrf_field() }} {{ method_field('patch') }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="control-label"> Station name </label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $station->name) }}" required>
                    </div>
                    <label for="key-group" class="control-label"> Key </label>
                    <div id="key-group" class="input-group">
                        <input type="text" class="form-control" name="key" value="{{ old('key', $station->key) }}" disabled>
                        <span class="input-group-append">
                            <a href="{{ route('key.update', $station->id) }}" class="btn btn-outline-secondary">Change</a>
                        </span>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude" class="control-label">Latitude</label>
                                <input id="latitude" type="text" class="form-control" name="latitude" value="{{ old('latitude', $station->latitude) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude" class="control-label">Longitude</label>
                                <input id="longitude" type="text" class="form-control" name="longitude" value="{{ old('longitude', $station->longitude) }}" required>
                            </div>
                        </div>
                    </div>
                    <div id="mapid"></div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="Update" class="btn btn-primary">
                    <a href="{{ route('station.show', $station->id) }}" class="btn btn-secondary">Cancel</a>
                    <a href="#" id="center-btn" class="btn btn-secondary">Center map</a>
            </form>
                    <div class="float-right">
                        <form action="{{ route('station.destroy', $station->id) }}" method="POST">
                            {{ csrf_field() }}{{ method_field('delete') }}
                            <input type="submit" value="Delete" class="btn btn-danger">
                        </form>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
    integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
    crossorigin=""></script>
<script>
    var mapCenter = [{{ $station->latitude }}, {{ $station->longitude }}];
    var map = L.map('mapid').setView([{{ $station->latitude }}, {{ $station->longitude }}], {{ config('leaflet.zoom') }});

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
console.log(marker);
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
<script src="{{ asset('js/geolocation-map-center.js') }}"></script>
@endpush