## Table of contents
* [General info](#general-info)
* [Screenshots](#screenshots)
* [Technologies](#technologies)
* [Setup](#setup)
* [API](#api)
* [Weather station](#weather-station)
    * [Description](#description)
    * [Scheme](#scheme)
    * [Photos](#photos)

## General info
![Scheme](https://user-images.githubusercontent.com/28191331/106015697-068f3280-60bf-11eb-90ad-8b608dd861dc.png)
The aim of the work is to design and build a web application that processes data from weather stations. This project is divided into a server application and the construction of a sample weather station providing data to the server.

## Screenshots
![dark_theme](https://user-images.githubusercontent.com/28191331/106016574-e3b14e00-60bf-11eb-93b4-f24d8522ad2d.png)
![data_old](https://user-images.githubusercontent.com/28191331/106016583-e4e27b00-60bf-11eb-9a94-77a0bdaf6029.png)
![station_view](https://user-images.githubusercontent.com/28191331/106016585-e57b1180-60bf-11eb-9605-10852a925a18.png)
![main_page](https://user-images.githubusercontent.com/28191331/106016588-e57b1180-60bf-11eb-955f-b113be7c7757.png)
![creating_station_profile](https://user-images.githubusercontent.com/28191331/106016590-e613a800-60bf-11eb-9cf0-f4302348c0ea.png)


## Technologies
* Laravel 6.0.0
* Bootstrap 4
* Leaflet 1.5.1
* Chart.JS 2.8.0
* Arduino

## Setup

1. Clone the repository and `cd` into it
1. `composer install`
1. Rename or copy `.env.example` file to `.env`
1. Set your config in your `.env` file
1. `php artisan key:generate`
1. `npm install`
1. `npm run prod`
1. `php artisan migrate`
1. `php artisan serve`
1. Visit `localhost:8000` in your browser


## API
#### Readings endpoint

* **URL**
  /readings

* **Method:**
  `POST`
  
*  **URL Params**
   None
* **Data Params**
  `temperature=[required, numeric, between: -100, 100]`
  `pressure=[required, numeric, between: 900, 1100]`
  `humidity=[required, numeric, between: 0, 100]`
  `key=[required]`
  `email=[required]`

* **Success Response:**
  * **Code:** 200
    **Content:** `{
    "data": {
        "temperature": "10",
        "pressure": "980",
        "humidity": "30",
        "station_name": "Station",
        "post_date": "2021-01-01 10:10:10"
    }
}`
 
* **Error Response:**
  * **Code:** 400
    **Content:** `{
    "temperature": [
        "The temperature must be a number."
    ],
    "pressure": [
        "The pressure must be between 900 and 1100."
    ],
    "humidity": [
        "The humidity field is required."
    ]
}`

   OR

  * **Code:** 401
    **Content:** `{
    "error": "Invalid email"
}`

  OR

  * **Code:** 401
    **Content:** `{
    "error": "Invalid token"
}`

## Weather station
#### Description
This project is an example implementation of a station providing data to a server. Each user can build his own project. The only condition is that the shared data must comply with the API requirements.
To reduce costs and make it easier to create such a station, the project is based on the BME280 and the NodeMCU.
#### Scheme
![kicad_scheme](https://user-images.githubusercontent.com/28191331/106016082-65ed4280-60bf-11eb-8fea-2eef9a9b1852.png)

#### Photos
![station](https://user-images.githubusercontent.com/28191331/106016359-aea4fb80-60bf-11eb-9e71-ef872f64aa69.jpg)
![station_inside](https://user-images.githubusercontent.com/28191331/106016276-959c4a80-60bf-11eb-9e43-64f3975781ee.jpg)
