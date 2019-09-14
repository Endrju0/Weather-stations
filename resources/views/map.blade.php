@extends('layouts.main')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>
    
<style>
    #mapid { min-height: 500px; }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-body" id="mapid"></div>
</div>

@foreach ($stations as $station)
        <p> {{ $station->longitude }} </p>
        <p> {{ $station->latitude }} </p>
    @endforeach
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
    integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
    crossorigin=""></script>

<script>
    var map = L.map('mapid').setView([{{ config('leaflet.center_latitude') }}, {{ config('leaflet.center_longitude') }}], {{ config('leaflet.zoom') }});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    }).addTo(map);
</script>

<script>
    var stations = {!! json_encode($stations) !!};

    for(var station in stations) {
        
        var stationPoint = {
            "type": "Feature",
            "properties": {
                "popupContent": stations[station]['name']
            },
            "geometry": {
                "type": "Point",
                "coordinates": [stations[station]['longitude'], stations[station]['latitude']]
            }
        };
        var stationLayer = L.geoJSON(stationPoint, {
            pointToLayer: function (feature, latlng) {
                console.log(latlng)
                return L.marker(latlng);
            },
        }).addTo(map);
        stationLayer.bindPopup(stationPoint.properties.popupContent);
    }
</script>
 
@endpush