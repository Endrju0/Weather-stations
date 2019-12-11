@extends('layouts.main')

@section('title', 'Weather Stations - ' . $station->name)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>

<style>
    #mapid { min-height: 300px; }  
</style>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">{{ $station->name }}</div>
            <div class="card-body" id="mapid"></div>
            <div class="card-footer">
                <a href="{{ route('station.edit', $station->id ) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('station.pdf', $station->id) }}" class="btn btn-primary">Export to PDF</a>
                <div class="float-right">
                    <form action="{{ route('station-readings.destroy', $station->id) }}" method="POST">
                        {{ csrf_field() }}{{ method_field('delete') }}
                        <input type="submit" value="Restart" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Temperature</div>
            <div class="card-body">
                <canvas id="temperatureChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Humidity</div>
            <div class="card-body">
                <canvas id="humidityChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">Pressure</div>
            <div class="card-body">
                <canvas id="pressureChart"></canvas>
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
    // Leaflet
    var map = L.map('mapid').setView([{{ $station->latitude }}, {{ $station->longitude }}], {{ config('leaflet.zoom') }});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    }).addTo(map);

    var point = L.marker([{{ $station->latitude }}, {{ $station->longitude }}]).addTo(map);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
    // Readings
    var readings = {!! json_encode($stationReadings) !!};
    var timestamp = new Array();  
    var temperature = new Array();  
    var humidity = new Array();  
    var pressure = new Array();
    for(var reading in readings) {
        timestamp.push(readings[reading]['created_at']);
        temperature.push(readings[reading]['temperature']);
        humidity.push(readings[reading]['humidity']);
        pressure.push(readings[reading]['pressure']);
    }

    var backgroundColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-color');
    // Chart js object
    function generateChart(data, label, pressurePrimaryColor, pressureSecondaryColor, bgColor) {
        return pressureInfo = {
            type: 'line',

            data: {
                labels: timestamp,
                datasets: [{
                    backgroundColor: pressurePrimaryColor,
                    borderColor: pressureSecondaryColor,
                    data: data
                }]
            },

            // Configuration options go here
            options: {
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItems, data) { 
                            return tooltipItems.yLabel + ' ' + label;
                        }
                    },
                },
                scales: {
                    xAxes: [{
                        gridLines: { color: bgColor },
                        ticks: { fontColor: bgColor }
                        }],
                    yAxes: [{
                        ticks: {
                            userCallback: function(item) {
                                return item + ' ' + label;
                            },
                            fontColor: bgColor
                        },
                        gridLines: { color: bgColor }
                    }]
                },
            }
        };
    }
</script>

<script>
    //temperatureChart    
    var ctxTemperature = document.getElementById('temperatureChart').getContext('2d');
    var chartTemperature = new Chart( ctxTemperature, generateChart(temperature, '°C', 'rgba(255, 99, 133, 0.315)', 'rgb(255, 99, 132)', backgroundColor) );

    //humidityChart    
    var ctxHumidity = document.getElementById('humidityChart').getContext('2d');
    var chartHumidity = new Chart( ctxHumidity, generateChart(humidity, '%', 'rgba(2, 204, 255, 0.315)', 'rgb(2, 204, 255)', backgroundColor) );

    //pressureChart    
    var ctxPressure = document.getElementById('pressureChart').getContext('2d');
    var pressureSecondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--pressure-secondary-color');
    var pressurePrimaryColor = getComputedStyle(document.documentElement).getPropertyValue('--pressure-primary-color');
    
    var chartPressure = new Chart( ctxPressure, generateChart(pressure, 'hPa', pressureSecondaryColor, pressurePrimaryColor, backgroundColor) );

    // Swapping colors of chart (if dark_theme is called)
    function chartColorSwap() {
        pressureSecondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--pressure-secondary-color');
        pressurePrimaryColor = getComputedStyle(document.documentElement).getPropertyValue('--pressure-primary-color');
        backgroundColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-color');

        chartPressure.destroy();
        chartHumidity.destroy();
        chartTemperature.destroy();

        chartPressure = new Chart( ctxPressure, generateChart(pressure, 'hPa', pressureSecondaryColor, pressurePrimaryColor, backgroundColor) );
        chartHumidity = new Chart( ctxHumidity, generateChart(humidity, '%', 'rgba(2, 204, 255, 0.315)', 'rgb(2, 204, 255)', backgroundColor) );
        chartTemperature = new Chart( ctxTemperature, generateChart(temperature, '°C', 'rgba(255, 99, 133, 0.315)', 'rgb(255, 99, 132)', backgroundColor) );
    }
</script>
 
@endpush