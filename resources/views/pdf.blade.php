<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $station->name}}</title>
    <style>
      body {
        text-align: center;
        font-family: "Nunito", Arial;
      }
      table {
        border-collapse: collapse;
        width: 70%;
        margin-left: auto;
        margin-right: auto;
      }
      th {
        padding: 12px 50px;
        background-color: #007bff;
        color: white;
        border: 1px #fff;
        border-radius: 25px;
      }
      tr {
        padding: 12px 50px;
      }
      td {
        padding: 12px 50px;
        border-bottom: 1px solid #ddd;
      }
      .small {
        font-size: 50%;
      }
    </style>
  </head>
  <body>
    <h1>{{ $station->name}}</h1>
    <h5>{{ $station->latitude}} : {{ $station->longitude}}</h5>
    <h5>Generated at {{ $date }}</h5>
    <table>
      <tr>
        <th>MEASUREMENT</th>
        <th>VALUE</th>
      </tr>
      <tr>
        <td>Average temperature</td>
        <td>
          {{ $avgTemperature }}°C
        </td>
      </tr>
      <tr>
        <td>Average humidity</td>
        <td>
          {{ $avgHumidity }} %
        </td>
      </tr>
      <tr>
        <td>Average pressure</td>
        <td>
          {{ $avgPressure }} hPa
        </td>
      </tr>
      <tr>
        <td>Min temperature</td>
        <td>
          {{ $minTemperature->temperature }}°C
          <p class="small">{{ $minTemperature->created_at }}</p>
        </td>
      </tr>
      <tr>
        <td>Min humidity</td>
        <td>
          {{ $minHumidity->humidity }} %
          <p class="small">{{ $minHumidity->created_at }}</p>
        </td>
      </tr>
      <tr>
        <td>Min pressure</td>
        <td>
          {{ $minPressure->pressure }} hPa
          <p class="small">{{ $minPressure->created_at }}</p>
        </td>
      </tr>
      <tr>
        <td>Max temperature</td>
        <td>
          {{ $maxTemperature->temperature }}°C
          <p class="small">{{ $maxTemperature->created_at }}</p>
        </td>
      </tr>
      <tr>
        <td>Max humidity</td>
        <td>
          {{ $maxHumidity->humidity }} %
          <p class="small">{{ $maxHumidity->created_at }}</p>
        </td>
      </tr>
      <tr>
        <td>Max pressure</td>
        <td>
          {{ $maxPressure->pressure }} hPa
          <p class="small">{{ $maxPressure->created_at }}</p>
        </td>
      </tr>
    </table>
  </body>
</html>
