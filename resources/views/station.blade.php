@extends('layouts.main')

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
    var map = L.map('mapid').setView([{{ $station->latitude }}, {{ $station->longitude }}], {{ config('leaflet.zoom') }});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    }).addTo(map);

    var point = L.marker([{{ $station->latitude }}, {{ $station->longitude }}]).addTo(map);
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
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
</script>

<script>
    //temperatureChart    
    Chart.defaults.global.defaultFontColor=getComputedStyle(document.documentElement).getPropertyValue('--chart-color');
    var ctx = document.getElementById('temperatureChart').getContext('2d');
    var chartTemperature = new Chart(ctx, {
        type: 'line',

        data: {
            labels: timestamp,
            datasets: [{
                backgroundColor: 'rgba(255, 99, 133, 0.315)',
                borderColor: 'rgb(255, 99, 132)',
                data: temperature
            }]
        },
        options: {
            legend: {
                display: false
            },tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) { 
                        return tooltipItems.yLabel + '°C';
                    }
                },
            },
            scales: {
                xAxes: [{gridLines: { color: getComputedStyle(document.documentElement).getPropertyValue('--chart-bg-color') }}],
                yAxes: [{
                    ticks: {
                        userCallback: function(item) {
                            return item + '°C';
                        },
                    },
                    gridLines: { color: getComputedStyle(document.documentElement).getPropertyValue('--chart-bg-color') }
                }]
            },
        }
    });
</script>
<script>
    //humidityChart    
    var ctx = document.getElementById('humidityChart').getContext('2d');
    var chartHumidity = new Chart(ctx, {
        type: 'line',

        data: {
            labels: timestamp,
            datasets: [{
                backgroundColor: 'rgba(2, 204, 255, 0.315)',
                borderColor: 'rgb(2, 204, 255)',
                data: humidity
            }]
        },
        options: {
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) { 
                        return tooltipItems.yLabel + ' %';
                    }
                },
            },
            scales: {
                xAxes: [{gridLines: { color: getComputedStyle(document.documentElement).getPropertyValue('--chart-bg-color') }}],
                yAxes: [{
                    ticks: {
                        userCallback: function(item) {
                            return item + ' %';
                        },
                    },
                    gridLines: { color: getComputedStyle(document.documentElement).getPropertyValue('--chart-bg-color') }
                }]
            },
        }
    });
</script>

<script>
    //pressureChart    
    var ctx = document.getElementById('pressureChart').getContext('2d');
    var chartPressure = new Chart(ctx, {
        type: 'line',

        data: {
            labels: timestamp,
            datasets: [{
                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--pressure-secondary-color'),
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--pressure-primary-color'),
                data: pressure
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
                        return tooltipItems.yLabel + ' hPa';
                    }
                },
            },
            scales: {
                xAxes: [{gridLines: { color: getComputedStyle(document.documentElement).getPropertyValue('--chart-bg-color') }}],
                yAxes: [{
                    ticks: {
                        userCallback: function(item) {
                            return item + ' hPa';
                        },
                    },
                    gridLines: { color: getComputedStyle(document.documentElement).getPropertyValue('--chart-bg-color') }
                }]
            },
        }
    });
</script>
 
@endpush