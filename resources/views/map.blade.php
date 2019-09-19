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
    //get stations latlng, name
    axios.get('{{ route('stations.index') }}')
    .then(function (response) {
        // pin stations to map
        L.geoJSON(response.data, {
            pointToLayer: function(geoJsonStation, latlng) {
                // console.log(geoJsonStation);
                return L.marker(latlng);
            }
        })
        .bindPopup('Loading...')
        //if click get fresh station readings
        .on('click', function(e) {
            var popup = e.target.getPopup();
            popup.setContent('Loading...')

            // console.log(e.layer.feature.properties.stationID);
            var url_api = '{{ route('readings.show', ":id") }}';
            url_api = url_api.replace(':id',e.layer.feature.properties.stationID);
            
            var url_station = '{{ route('station.show', ":id") }}';
            url_station = url_station.replace(':id',e.layer.feature.properties.stationID);

            axios.get(url_api)
            .then(function (response) {
                console.log(response);
                popup.setContent(
                    '<p><a href="' + url_station + '"><b>' + response.data.name + '</b></a></p>' +
                    '<p> Temperature: ' + response.data.temperature + '</p>' +
                    '<p> Pressure: ' + response.data.pressure + '</p>' +
                    '<p> Humidity: ' + response.data.humidity + '</p>'
                );
                popup.update();
            })
            .catch(function (error) {
                console.log(error);
            });

        })
        .addTo(map);
    })
    .catch(function (error) {
        console.log(error);
    });
</script>
 
@endpush