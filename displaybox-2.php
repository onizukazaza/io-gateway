<?php 
    require_once 'controllers/server.php';
    if (!isset($_SESSION['user_id'])) {
        header('location: login.php');
    }

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show and Close Display Box</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Fira+Sans+Condensed&display=swap');

.display-box {
    display: none;
    background: linear-gradient(135deg, #ffffff, #f3f5f7);
    border-radius: 12px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -60%) scale(0.9);
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), 0 0 15px rgba(0, 0, 0, 0.1);
    transition: opacity 0.4s, transform 0.4s;
    opacity: 0;
    width:800px;
    height: 660px;
     position: absolute;

     z-index: 1001;
}

pre {
    background-color: #f4f4f4;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 16px;
    overflow-x: auto; 
    overflow-y: auto; 
    max-height: 200px; 
}

.display-box.show {
    display: block;
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}
.display-box p {
    font-size: 16px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 20px;
    font-family: 'Fira Sans Condensed', sans-serif;

}

.display-box h1 {
    font-size:21px;
    color: #333;
    line-height: 1.6;
    margin-bottom: 20px;
    font-family: 'Fira Sans Condensed', sans-serif;
    
}
.display-box h3 {
    font-size:17px;
    color: #333;
    line-height: 1.5;
    margin-bottom: 20px;
    font-family: 'Fira Sans Condensed', sans-serif;

    
}
.display-box li {
    font-size:16px;
    color: #333;
    line-height: 1.5;
    margin-bottom: 5px;
    font-family: 'Fira Sans Condensed', sans-serif;
}
.close-button {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #aaa;
    transition: color 0.3s, transform 0.3s;
}

.close-button:hover {
    color: #777;
    transform: rotate(90deg);
}

#toggleButton {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
    border-radius: 50px; 
    padding: 10px 30px;  
    font-size: 16px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
}

#toggleButton:hover {
    background: linear-gradient(135deg, #0056b3, #003f8a);
    box-shadow: 0 5px 15px rgba(0, 56, 179, 0.2);
    transform: translateY(-3px);
}

.hidden {
    display: none;
}

.nextButton {
    background-color: #007bff;
    color: white;
    padding: 8px 16px;  
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 14px;  
}
.nextButton:hover {
    background-color: #0056b3;
}
/* ตกแต่งปุ่ม Back */
.backButton {
    background-color: #6c757d;
    color: white;
    padding: 8px 16px;  
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 14px; 
}
.backButton:hover {
    background-color: #5a6268;
}

pre {
    background-color: #f4f4f4;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 16px;
    overflow-x: auto;  
}
code {
    font-family: 'Courier New', Courier, monospace;  
    color: #333;
    font-size: 16px;
}

.button-container {
    position: absolute;
    bottom: 0;
    right: 0;
    margin: 20px;
}
    </style>
</head>
<body>
    
    <div class="display-box" id="displayBox">
        <button class="close-button" id="closeButton">&times;</button>

        <div class="page" id="page1">
            <h1>Steps for Using</h1>
            <p>This section outlines how to use the device with the "IO Gateway" web interface. Users can write code from the Arduino side to send data to the web. The requirements are straightforward; you need an ESP8266. Users are free to use the prepared code, just change the commented values to fit your use case.</p>
<h3>Requirements</h3>
<ul>
    <li>ESP8266 boards (x1)</li>
    <li>Arduino IDE</li>
</ul>
<h3>Steps to Follow</h3>
<ol>
    <li>Modify the code according to the comments we've added</li>
    <li>Upload the code to the ESP8266 board</li>
</ol>
            <div class="button-container">
    <button class="nextButton">Next</button>
</div>
</div>




        <div class="page hidden" id="page2">
<h1>Code that needs</h1>
    <h3>Import library, import the ESP8266WiFi and ESP8266HTTPClient libraries.</h3>
    <pre><code>
#include&lt;ESP8266WiFi.h&gt;
#include&lt;ESP8266HTTPClient.h&gt;
    </code></pre>

    <h3>Set constant values for WiFi SSID and password</h3>
    <pre><code>
const char* ssid = ""; // Enter your WiFi SSID name
const char* password = ""; // Enter your WiFi password name
    </code></pre>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>


<div class="page hidden" id="page3">
<h1>Code that needs</h1>
       <h3>auth token (this script is used to communicate with IO Gateway) HTTP request</h3>
    <pre><code>
#define YOUR_USER_TOKEN "80913d9585c08480500ff41c57e1faa2" //Your token
#define YOUR_DEVICENAME "dev_1" //Your device
#define SERVER_URL "http://www.iogateway.com" //the webserver of this website
    </code></pre>
<h3>#define YOUR_USER_TOKEN</h3>
            <ul>
                <li>A user's token or 'token' is used to identify the user or device sending data to the server. This is for security and managing access permissions</li>
            </ul>
            <h3>#define YOUR_DEVICENAME</h3>
            <ul>
                <li>The name of the device used to send data is utilized to identify the device transmitting the data, which is beneficial for managing multiple devices</li>
            </ul>
                        <h3>#define SERVER_URL</h3> 
            <ul>
                <li>The URL of the web server where the data is stored. Data will be sent to this URL via an HTTP POST request</li>
            </ul>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>


<div class="page hidden" id="page4">
    <h1>Code details</h1>
    <h3>This code is an Arduino program for boards using the ESP8266 WiFi module in conjunction with the DHT11 humidity and temperature sensor (or other versions of the DHT sensor).</h3>
    <h3>setup() Function</h3>
            <ol>
            <li>Initializes the operation of the DHT sensor.</li>
            </ol>
     <h3>sendSensorData() Function</h3>
     <ol>
            <li>This function is for sending data from the sensor to the web server via an HTTP POST request.</li>
            </ol>
    <h3>loop() Function</h3>
     <ol>
            <li>Reads humidity (h) and temperature (t) data from the sensor.</li>
            <li>Sends the data to the web server if the read data is valid.</li>
            </ol>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>


<div class="page hidden" id="page5">
    <h1>Code that needs to be prepared</h1>
    <h3>Library Inclusion</h3>
    <pre><code>
#include &lt;ESP8266WiFi.h&gt;
#include &lt;ESP8266HTTPClient.h&gt;
#include &lt;DHT.h&gt;
</code></pre>
     <h3>Setting Up the DHT Sensor</h3>
<pre><code>
#define DHTPIN D4 // The pin connected from the sensor
#define DHTTYPE DHT11 // Your DHT sensor type
DHT dht(DHTPIN, DHTTYPE);
</code></pre>
  <h3>Configuring the DHT Sensor to Connect to Pin D4 and is of Type DHT11</h3>
  <p>This part of the code involves including libraries for connecting to WiFi, using HTTP client, and reading data from the DHT sensor. The DHT sensor in this code is configured to be connected to pin D4 and is of type DHT11.</p>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>

<div class="page hidden" id="page6">
    <h1>Code that needs to be prepared</h1>
    <h3>setup()</h3>
    <pre><code>
    void setup() {
  Serial.begin(115200);
  dht.begin();
  WiFi.begin(ssid, password);
  while (WiFi.status() !=  WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}
</code></pre>
<p>Initiating Serial Communication and DHT11 sensor operation.</p>
     <h3>sendSensorData()</h3>
<pre style ="max-height: 135px"><code>
void sendSensorData(const char* gpio_pin, float value) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    WiFiClient client;
    http.begin(client, SERVER_URL); 
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
    String postData = "devicename=" + String(DEVICE_NAME) + "&gpio=" + String(gpio_pin) + "&value=" + String(value) + "&usertoken=" + USER_TOKEN;
    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.println("Error on HTTP request");
    }
    http.end();
  }
}

</code></pre>
  <p>Receive the data to be sent (gpio_pin and value) and send the data to the server using an HTTP POST request.</p>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>


<div class="page hidden" id="page7">
    <h1>Code that needs to be prepared</h1>
    <h3>loop()</h3>
    <pre><code>
void loop() 
{
  float h = dht.readHumidity();
  float t = dht.readTemperature(); 
  Serial.print("Sensor 1 - Humidity: ");
  Serial.print(h1);
  Serial.print(" %, Temperature: ");
  Serial.print(t1);
  Serial.println(" °C");
  if (!isnan(h) && !isnan(t)) {
    sendSensorData("D2", h);
    sendSensorData("D6", t);
  } else {
    Serial.println("Failed to read from DHT sensor!");
  }
  delay(3000);
}
</code></pre>
<p>Measure humidity and temperature values from DHT11 sensor</p>
<p>Read the humidity (h) and temperature (t) values from the DHT11 sensor and store them in variables h and t respectively</p>
<p>if (!isnan(h) && !isnan(t)): Check if the values obtained from the sensor are not NaN (Not a Number). If true, proceed to send the data</p>
<p>Example sendSensorData("D2", h); and sendSensorData("D6", t);: This part of the code calls the sendSensorData function to send humidity and temperature values to the server</p>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>


    </div>
    <script>
    const toggleButton = document.getElementById('toggleButton');
    const closeButton = document.getElementById('closeButton');
    const displayBox = document.getElementById('displayBox');

    toggleButton.addEventListener('click', () => {
        if (displayBox.classList.contains('show')) {
            displayBox.classList.remove('show');
        } else {
            displayBox.classList.add('show');
        }
    });

    closeButton.addEventListener('click', () => {
        displayBox.classList.remove('show');
    });

    let currentPage = 0;
const pages = [
    document.getElementById('page1'),
    document.getElementById('page2'),
    document.getElementById('page3'),
    document.getElementById('page4'),
    document.getElementById('page5'),
    document.getElementById('page6'),
    document.getElementById('page7'),

];
 
const nextButtons = document.querySelectorAll('.nextButton');
const backButtons = document.querySelectorAll('.backButton');

nextButtons.forEach((nextButton) => {
    nextButton.addEventListener('click', () => {
        pages[currentPage].classList.add('hidden');
        currentPage = (currentPage + 1) % pages.length;
        pages[currentPage].classList.remove('hidden');
    });
});

backButtons.forEach((backButton) => {
    backButton.addEventListener('click', () => {
        pages[currentPage].classList.add('hidden');
        currentPage = (currentPage - 1 + pages.length) % pages.length;
        pages[currentPage].classList.remove('hidden');
    });
});

</script>
</body>
</html>

