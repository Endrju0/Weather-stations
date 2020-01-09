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
            @if(Auth::id() == $station->user_id)
                <div class="card-footer d-flex flex-row w-100">
                    <a href="{{ route('station.edit', $station->id ) }}" class="btn btn-primary mr-1">Edit</a>
                    <a href="{{ route('station.pdf', $station->id) }}" class="btn btn-primary mr-1">Export to PDF</a>
                    <a href="{{ route('station.date.show', $station->id) }}" class="btn btn-primary mr-1">Select day</a>
                    {{-- <div class="dropdown show">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Filter
                        </a>
                        
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">Month</a>
                            <a class="dropdown-item" href="#">Week</a>
                            <a class="dropdown-item" href="#">Day</a>
                        </div>
                    </div> --}}
                    <div class="ml-auto">
                        <form action="{{ route('station-readings.destroy', $station->id) }}" method="POST">
                            {{ csrf_field() }}{{ method_field('delete') }}
                            <input type="submit" value="Restart" class="btn btn-danger">
                        </form>
                    </div>
                </div>
            @endif
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
<script src="https://cdn.jsdelivr.net/npm/moment@2.24.0/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
    // Readings
    var readings = {!! json_encode($stationReadings) !!};
    var stationID = {{ $station->id }};
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
    var configTemperature = generateChart(temperature, '°C', 'rgba(255, 99, 133, 0.315)', 'rgb(255, 99, 132)', backgroundColor);
    var chartTemperature = new Chart( ctxTemperature,  configTemperature );

    //humidityChart    
    var ctxHumidity = document.getElementById('humidityChart').getContext('2d');
    var configHumidity = generateChart(humidity, '%', 'rgba(2, 204, 255, 0.315)', 'rgb(2, 204, 255)', backgroundColor);
    var chartHumidity = new Chart( ctxHumidity, configHumidity );

    //pressureChart    
    var ctxPressure = document.getElementById('pressureChart').getContext('2d');
    var pressureSecondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--pressure-secondary-color');
    var pressurePrimaryColor = getComputedStyle(document.documentElement).getPropertyValue('--pressure-primary-color');
    var configPressure = generateChart(pressure, 'hPa', pressureSecondaryColor, pressurePrimaryColor, backgroundColor);
    var chartPressure = new Chart( ctxPressure, configPressure );

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
    
    // Update charts with latest data
    function chartUpdate() {
        var url_api = '{{ route('readings.show', ":id") }}';
        url_api = url_api.replace(':id',stationID);

        axios.get(url_api)
        .then(function (response) {
            if(response.data.timestamp != timestamp[timestamp.length-1]) {
                // Temperature chart update
                configTemperature.data.labels.shift();
                configTemperature.data.labels.push(response.data.timestamp);

                configTemperature.data.datasets[0].data.shift();
                configTemperature.data.datasets[0].data.push(response.data.temperature);
                chartTemperature.update();

                // Pressure chart update
                configHumidity.data.labels.shift();
                configHumidity.data.labels.push(response.data.timestamp);

                configHumidity.data.datasets[0].data.shift();
                configHumidity.data.datasets[0].data.push(response.data.humidity);
                chartHumidity.update();

                // Humidity chart update
                configPressure.data.labels.shift();
                configPressure.data.labels.push(response.data.timestamp);

                configPressure.data.datasets[0].data.shift();
                configPressure.data.datasets[0].data.push(response.data.pressure);
                chartPressure.update();
            }
        })
        .catch(function (error) {
            console.log(error);
        });

    };
    setInterval(chartUpdate, 30000); // 30s
</script>
 
@endpush