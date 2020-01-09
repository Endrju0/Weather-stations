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
            <div class="card-header">Show station readings at specified date</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name" class="control-label"> Station name </label>
                    <h3>{{ $station->name }}</h3>
                </div>
                <div class="form-group mt-3">
                    <label for="query-group" class="control-label"> Select date </label>
                    <form id="query-group" class="input-group" method="GET" action="">
                        <input type="date" name="query" class="form-control" value="{{ $query }}">
                        <span class="input-group-append">
                            <input type="submit" value="Show" class="btn btn-outline-secondary">
                        </span>
                    </form>
                </div>
                <div id="date-container" class="form-group">
                    @if($readings != null)
                        @foreach ($readings as $reading)
                            @if($loop->count == 1)
                            <p>Temperature: <span class="text-primary">{{ $reading->temperature }} °C</span></p>
                            <p>Pressure: <span class="text-primary">{{ $reading->pressure }} hPa</span></p>
                            <p>Humidity: <span class="text-primary">{{ $reading->humidity }} %</span></p>
                            <p>Retrived at: {{ $reading->created_at }}</p>
                            @elseif($loop->last)
                                <div class="col-12">
                                    <hr>
                                    <h3>Temperature</h3>
                                    <canvas id="temperatureChart"></canvas>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <h3>Humidity</h3>
                                    <canvas id="humidityChart"></canvas>
                                    <hr>
                                </div>
                                <div class="col-12">
                                    <h3>Pressure</h3>
                                    <canvas id="pressureChart"></canvas>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('station.show', $station->id) }}" class="btn btn-secondary">Return</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
    // Readings
    var readings = {!! json_encode($readings) !!};
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
        var config = {
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

        return config;
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