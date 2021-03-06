@extends('layouts.main')

@section('title', 'Weather Stations - Map')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>
    
<style>
    #mapid { height: 500px; }
    .another-popup .leaflet-popup-content-wrapper {
        background: rgba(0, 0, 0, 0.6);
        padding: 0;
        margin: 0;
        color: #ff0000;
        font-size: 15px;
        font-weight: 600;
        line-height: 2px;
        border-radius: 0px;
        text-align: left;
        box-shadow: none;
    }
    .another-popup .leaflet-popup-content-wrapper a {
        color: transparent; 
    }
    .another-popup .leaflet-popup-tip-container {
        width: 57px;
        height: 15px;
    }
    .another-popup .leaflet-popup-tip {
        background: transparent;
        border: none;
        box-shadow: none;
    }
    .popup-text-small {
        font-size: 0.7em;
    }

    @media only screen and (max-height: 600px) {
        #mapid { height: 70vh; }
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-body custom-popup" id="mapid"></div>
    <div class="card-footer">
        <div id="filters" class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-outline-secondary">
              <input type="checkbox" name="temperature" autocomplete="off"> Temperature
            </label>
            <label class="btn btn-outline-secondary">
                <input type="checkbox" name="pressure" autocomplete="off"> Pressure
            </label>
            <label class="btn btn-outline-secondary">
                <input type="checkbox" name="humidity" autocomplete="off"> Humidity
            </label>
          </div>
        
        <a href="#" class="btn btn-secondary" onclick="filter();">Show</a>
        <a href="#" id="center-btn" class="btn btn-secondary">Center map</a>
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
    // Map
    var map = L.map('mapid').setView(mapCenter, {{ config('leaflet.zoom') }});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    }).addTo(map);

    // Red marker
    var redMarker = L.icon ({
        iconUrl: "{{ asset('marker-red.png') }}",
        iconAnchor: [12, 40],
        popupAnchor: [0, -32],
    });
    // Get stations latlng, name (popup info)
    axios.get('{{ route('stations.index') }}', {
        params: {
            user_id: {{ Auth::id() }}
        }
    })
    .then(function (response) {
        // pin stations to map
        L.geoJSON(response.data, {
            pointToLayer: function(geoJsonStation, latlng) {
                if(geoJsonStation.properties.ownerID == {{ Auth::id() }})
                    return L.marker(latlng, {icon: redMarker});
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
                    '<p><a href="' + url_station + '"><b>' + e.layer.feature.properties.name + '</b></a></p>' +
                    '<p> Temperature: ' + Math.round(response.data.temperature * 100) / 100 + ' °C</p>' +
                    '<p> Humidity: ' + Math.round(response.data.humidity * 100) / 100  + ' %</p>' +
                    '<p> Pressure: ' + Math.round(response.data.pressure * 100) / 100  + ' hPa</p>' +
                    '<p class="popup-text-small">' + response.data.timestamp + '</p>'
                );
                popup.update();
            })
            .catch(function (error) {
                console.log(error);
                popup.setContent(
                    '<p><a href="' + url_station + '"><b>' + e.layer.feature.properties.name + '</b></a></p>' +
                    '<p> Temperature: no data</p>' +
                    '<p> Humidity: no data</p>' +
                    '<p> Pressure: no data</p>'
                );
                popup.update();
            });

        })
        .addTo(map);
    })
    .catch(function (error) {
        console.log(error);
    });

    // Filters
    var filtersPane;
    function filter() {
        // Refresh popups
        if(L.DomUtil.get(filtersPane)) L.DomUtil.remove(filtersPane);

        filtersPane = map.createPane("filtersPane");
        filtersPane.style.zIndex = 500;

        // Set new popups based on selected filters
        var checks = document.getElementById('filters').getElementsByTagName('input');
        var filters = {!! json_encode($filters) !!};

        filters.forEach(filter => {
            var output = '<span>';
            Array.from(checks).forEach((el) => {
                if(el.checked && filter.readings != null) {
                    if(el.name == 'temperature') output += String(Math.round(filter.readings.temperature * 100) / 100) + ' °C<br>'; 
                    if(el.name == 'humidity')  output += String(Math.round(filter.readings.humidity * 100) / 100) + ' %<br>'; 
                    if(el.name == 'pressure')  output += String(Math.round(filter.readings.pressure * 100) / 100) + ' hPa<br>'; 
                }
                output += '</span>';
            })
            new L.Popup({
                closeButton: false,
                closeOnClick: false,
                autoPan: false,
                pane: "filtersPane",
                autoClose: false,
                offset: [65, 30],
                className: 'another-popup'
            }).setLatLng([filter.latitude, filter.longitude]).setContent(
                output
            ).addTo(map);
        });
    }
</script>
<script src="{{ asset('js/geolocation-map-center.js') }}"></script>
 
@endpush