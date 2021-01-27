#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <Adafruit_BME280.h>
#include <Adafruit_Sensor.h>

#define SEALEVELPRESSURE_HPA (1013.25) // Average Standard Atmosphere (ISA) 1013.25 hPa
#define LED_BUILTIN 2

Adafruit_BME280 bme; // I2C

const char* ssid     = "WIFI_SSID";
const char* password = "WIFI_PASSWORD";
const char* email = "USER@EMAIL";
const char* station_key = "STATION_KEY";
const char* address = "APP_URL/api/readings";

// Set web server port number to 80
WiFiServer server(80);

// Variable to store the HTTP request
String header;

void setup() {
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);
  bool status;

  if (!bme.begin(0x76)) {
    Serial.println("Could not find a valid BME280 sensor, check wiring!");
    while (1);
  }

  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.println("WiFi connected.");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  
  server.begin();
}

void loop(){
   if(WiFi.status()== WL_CONNECTED){
   digitalWrite(LED_BUILTIN, HIGH);
   HTTPClient http;
 
   http.begin(address);
   http.addHeader("Content-Type", "application/x-www-form-urlencoded")
   
   String post_body = "key=";
   post_body += station_key;
   post_body += "&email=";
   post_body += email;
   post_body += "&temperature=" + (String) bme.readTemperature() +
                      "&pressure=" +  (String) (bme.readPressure() / 100.0F) + "&humidity=" + (String) bme.readHumidity();
   int httpCode = http.POST(post_body);
   String payload = http.getString();
 
   Serial.println(httpCode);
   Serial.println(payload);
 
   http.end();
 
 } else {
 
    Serial.println("Error in WiFi connection");   
    digitalWrite(LED_BUILTIN, LOW);
 }
 
  delay(1800000);  // 30 min
}
