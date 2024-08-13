
<?php 
    require_once 'controllers/server.php';
    if (!isset($_SESSION['user_id'])) {
        // $_SESSION['error'] = 'Please log in!';
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
    width: 800px;
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
    margin-bottom: 6px;
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
            <p>In this page, the data of the pairing of 2 devices is displayed. Users can write code from the Arduino side to send data to the "IO Gateway" web. What users need to prepare are 2 ESP8266  boards. Users can freely use the code that we have prepared by just changing the values where we have commented according to the user's application</p>
            <h3>Requirements</h3>
            <ul>
                <li>ESP8266 boards (x2)</li>
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
    <h1>Code details (code on the INPUT)</h1>
    <h3>This code uses ESP8266 to read the status of a switch or button connected to a pin, then it sends the button's status to a server at the specified URL via an HTTP POST request. The functions of each part of the code are as follows</h3>
    <h3>setup()</h3>
    <ol>
    <li>Configure the switch or button connected to the pin to function as pull-up input.</li>
    <li>Connect to WiFi and display a message when the connection is successful.</li>
</ol>
<h3>sendData()</h3>
<ol>
    <li>Create an HTTP POST request and send the 'devicename', 'gpio', 'value', and 'usertoken' data to the server.</li>
    <li>Receive and display the HTTP response code and response body.</li>
</ol>
<h3>loop()</h3>
<ol>
    <li>Check if the WiFi is still connected.</li>
    <li>Read and send the status of the switch or button connected to the specified pin to the server.</li>
    <li>Delay for 2 seconds after sending the status of all buttons.</li>
</ol>

    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>
<div class="page hidden" id="page5">
<h1>Code that needs to be prepared (code on the INPUT )</h1>
    <h3>Code that needs to be prepared (code on the INPUT side)</h3>
    <pre><code>
#define SWITCH_PIN1 D1
#define SWITCH_PIN2 D2
#define SWITCH_PIN4 D4
    </code></pre>
    <h3>Setting up</h3>
    <pre><code>
    void setup() {
    Serial.begin(115200);
    pinMode(SWITCH_PIN1, INPUT_PULLUP);
    pinMode(SWITCH_PIN2, INPUT_PULLUP); 
    pinMode(SWITCH_PIN3, INPUT_PULLUP); 
    WiFi.begin(ssid, password); 
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Connecting to WiFi...");
    }
    
    Serial.println("Connected to WiFi");
}
    </code></pre>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>
<div class="page hidden" id="page6">
<h1>Code that needs to be prepared (code on the INPUT )</h1>
    <h3>The function sendData(const char* gpio, const String& status) in this code is responsible for sending data to the server via an HTTP POST request</h3>
    <pre><code>
 void sendData(const char* gpio, const String& value) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        WiFiClient client; 
        http.begin(client, SERVER_URL);  
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");
        String postData = "devicename=" + String(YOUR_DEVICENAME) 
            + "&gpio=" + gpio + "&value=" + value 
            + "&usertoken=" + String(YOUR_USER_TOKEN);
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
    <ol>
    <li>HTTPClient http; WiFiClient client;: Create objects http and client from the class.</li>
    <li>http.begin(client, SERVER_URL);: Instruct the http object to initiate a connection to the specified URL.</li>
    <li>String postData =: Create a string postData that includes all data to be sent in the form of application/x-www-form-urlencoded.</li>
    <li>int httpResponseCode = http.POST(postData);: Send an HTTP POST request and store the HTTP response code in the variable httpResponseCode.</li>
</ol>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>

<div class="page hidden" id="page7">
<h1>Code that needs to be prepared (code on the INPUT)</h1>
    <h3>loop()</h3>
    <pre><code>
    void loop() {
     sendData("D1", digitalRead(SWITCH_PIN1) ? "On" : "Off");
     sendData("D2", digitalRead(SWITCH_PIN2) ? "On" : "Off");
     sendData("D3", digitalRead(SWITCH_PIN3) ? "On" : "Off");
delay(1000);  
}
    </code></pre>
    <ol>
    <li>if (WiFi.status() == WL_CONNECTED) { ... }: Checks if the device is successfully connected to Wi-Fi. If it is, the code inside the block will be executed.</li>
    <li>sendData("D1", digitalRead(SWITCH_PIN1) ? "On" : "Off");: Reads the status of the SWITCH_PIN1 and sends the data to the server using the sendData function. If the pin status is HIGH (returns true from digitalRead), it sends the status "On." If LOW, it sends "Off."</li>
    <li>The same code is repeated for SWITCH_PIN2, SWITCH_PIN3, and SWITCH_PIN4 sequentially.</li>
    <li>delay(2000);: Delays for 2 seconds after sending all data.</li>
</ol>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>


<div class="page hidden" id="page8">
  <h1>Code details (code on the OUTPUT)</h1>
  <h3>โ"This code is for ESP8266 used to control the status of LEDs by using HTTP POST requests sent to the server to receive the status of each LED. Then, it changes the LED status according to what is received. The responsibilities of each part of the code are as follows</h3>
  <h3>setup()</h3>
<ol>
    <li>Set pins for the LED to OUTPUT.</li>
    <li>Connect to Wi-Fi.</li>
    <li>Display a message when the Wi-Fi connection is successful.</li>
</ol>
<h3>setLEDStatus()</h3>
<ol>
    <li>Check if there's a Wi-Fi connection.</li>
    <li>Create an HTTP POST request in the format of application/x-www-form-urlencoded.</li>
    <li>Send the 'devicename', 'gpio', and 'usertoken' data to the server.</li>
    <li>Receive the LED status in return (either "On" or "Off") and set the pin according to the received status.</li>
</ol>
<h3>loop()</h3>
<ol>
    <li>This code calls setLEDStatus() for each LED with a delay of 500 milliseconds between each call.</li>
    <li>After receiving the status of all LEDs, there's a delay of 1 second (1000 milliseconds) before starting a new loop.</li>
</ol>
    <div class="button-container">
    <button class="backButton">Back</button>
      <button class="nextButton">Next</button>
   </div>
</div>

<div class="page hidden" id="page9">
<h1>Code to Prepare (Code on the OUTPUT)</h1>
<h3>Assign the GPIO pins according to the pins you have selected, for example (D6, D5, D4) that are used to connect with the LEDs</h3>
<pre><code>
#define LED_PIN1 D6
#define LED_PIN2 D5
#define LED_PIN3 D4
</code></pre>
<h3>Initial Settings</h3>
<pre><code>
void setup() {
  Serial.begin(115200); // Set the communication speed of the Serial port to 115200 baud. Serial communication is used to view data and messages that the board outputs
  pinMode(LED_PIN1, OUTPUT); // Set the GPIO pin connected to the first LED (LED_PIN1 or D6) as OUTPUT
  pinMode(LED_PIN2, OUTPUT);
  pinMode(LED_PIN3, OUTPUT);
  WiFi.begin(ssid, password);
  while (WiFi.status() !=  WL_CONNECTED) { // Loop continuously until the Wi-Fi connection is successful
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}
</code></pre>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>

<div class="page hidden" id="page10">
<h1>Code to Prepare (Code on the OUTPUT)</h1>
<h3>The setLEDStatus() function in this code is responsible for controlling the LED status by sending an HTTP POST request to the server and receiving a response to set the LED as specified.</h3>
  <pre><code>
  void setLEDStatus(const char* gpio, uint8_t pin) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    WiFiClient client;  
    String url = String(SERVER_URL) 
                 + "?devicename=" + String(YOUR_DEVICENAME) 
                 + "&gpio=" + gpio 
                 + "&usertoken=" + String(YOUR_USER_TOKEN);
    http.begin(client, url);  
    int httpResponseCode = http.GET();
    if (httpResponseCode > 0) {
      String response = http.getString();
      if (response == "On") {
        digitalWrite(pin, HIGH);
      } else if (response == "Off") {
        digitalWrite(pin, LOW);
      }
      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.println("Error on HTTP request");
    }
    http.end();
  }
}
    </code></pre>
    <ol>
    <li>HTTPClient http; WiFiClient client;: Create http and client objects from the class.</li>
    <li>http.begin(client, url);: Instruct the http object to initiate a connection to the specified URL.</li>
    <li>Use http.GET() to execute the HTTP GET command and store the HTTP response code.</li>
    <li>if (response == "On"): If the response text is "On", set the LED to HIGH (turn on).</li>
    <li>else if (response == "Off"): If the response text is "Off", set the LED to LOW (turn off).</li>
</ol>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>
<div class="page hidden" id="page11">
<h1>Code to Prepare (Code on the OUTPUT)</h1>
<h3>In the void loop() section, this function will continuously operate when the program starts running, by calling the setLEDStatus function multiple times with different parameters to control the LED.</h3>
  <pre><code>
  void loop() {
  setLEDStatus("D6", LED_PIN1);
  setLEDStatus("D5", LED_PIN2);
  setLEDStatus("D4", LED_PIN3);
  delay(1000);
}
    </code></pre>
    <div class="button-container">
    <button class="backButton">Back</button>
    <button class="nextButton">Next</button>
</div>
</div>  

<div class="page hidden" id="page12">
<h1>Code Details (Both INPUT and OUTPUT)</h1>
<h3>If you want the ESP8266 board to serve both as Input and Output, or to have a switch sending to another ESP8266 board that controls an LED and also have an LED receiving values from the switch of another board, each part of the code serves the following purposes:</h3>
<h3>setup()</h3>
<ol>
    <li>Set the pin for the SWITCH as INPUT and the pin for the LED as OUTPUT.</li>
    <li>Connect to Wi-Fi.</li>
    <li>Display a message when the Wi-Fi connection is successful.</li>
</ol>
<h3>sendSwitchData()</h3>
<ol>
    <li>Check if there is a Wi-Fi connection.</li>
    <li>Create an HTTP POST request and send the switch status data to the web server.</li>
    <li>Send 'devicename', 'gpio', 'value', and 'usertoken' data to the server.</li>
</ol>
<h3>getAndSetLEDStatus()</h3>
<ol>
    <li>Check if there is a Wi-Fi connection.</li>
    <li>Create an HTTP GET request to receive the LED status from the web server.</li>
    <li>Send 'devicename', 'gpio', and 'usertoken' data to the server.</li>
    <li>Set the LED status according to the response received from the web server.</li>
    <li>Receive the LED status (either "On" or "Off") and set the pin according to the received status.</li>
</ol>
    <div class="button-container">
    <button class="backButton">Back</button>
      <button class="nextButton">Next</button>
   </div>
</div>


<div class="page hidden" id="page13">
<h1>Code Details (Both INPUT and OUTPUT Sides)</h1>
<h3>loop()</h3>
<ol>
    <li>Call sendSwitchData() to send the status of the switch.</li>
    <li>Call getAndSetLEDStatus() to receive and set the status of the LED.</li>
</ol>
<h3>URL of the server used to exchange data with the ESP8266</h3>
<pre><code>
#define SERVER_URL_INSERT "http://ip address/io_gateway/ESPDataHandling/insert_data.php"
#define SERVER_URL_GET "http://ip address/io_gateway/ESPDataHandling/get_status.php"
</code></pre>
<ol>
    <li>SERVER_URL_INSERT is used when the ESP8266 needs to send the status of the switch or other data to the server.</li>
    <li>SERVER_URL_GET is used when the ESP8266 wants to receive the current status of the LED from the server.</li>
</ol>
    <div class="button-container">
    <button class="backButton">Back</button>
      <button class="nextButton">Next</button>
   </div>
</div>

<div class="page hidden" id="page14">
<h1>Code to Prepare (Both INPUT and OUTPUT)</h1>
<h3>Assign the GPIO pins according to your selection, for instance, D1 connected to SWITCH and D2 connected to LED.</h3>
<pre><code>
#define SWITCH_PIN1 D1
#define LED_PIN1 D2
</code></pre>
<h3>Initial Setup</h3>
<pre><code>
void setup() {
  Serial.begin(115200);
  pinMode(SWITCH_PIN1, INPUT_PULLUP);
  pinMode(LED_PIN1, OUTPUT);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}
</code></pre>
    <div class="button-container">
    <button class="backButton">Back</button>
      <button class="nextButton">Next</button>
   </div>
</div>

<div class="page hidden" id="page15">
<h1>Preparation of Code (For Both INPUT and OUTPUT)</h1>
<h3>sendSwitchData()</h3>
    <pre><code>
    void getAndSetLEDStatus(const char* gpio, const String& value) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    WiFiClient client;
    http.begin(client, SERVER_URL_INSERT);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  
    String postData = "devicename=" + String(YOUR_DEVICENAME)
                      + "&gpio=" + gpio + "&value=" + value
                      + "&usertoken=" + String(YOUR_USER_TOKEN);
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
    <h3>getAndSetLEDStatus()</h3>
    <pre><code>
    void getAndSetLEDStatus(const char* gpio, uint8_t pin) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    WiFiClient client;  // สร้าง instance ของ WiFiClient

    String url = String(SERVER_URL_GET) 
                 + "?devicename=" + String(YOUR_DEVICENAME) 
                 + "&gpio=" + gpio 
                 + "&usertoken=" + String(YOUR_USER_TOKEN);
    
    http.begin(client, url);  // ใส่ client ลงไปใน method begin()
    int httpResponseCode = http.GET();

    if (httpResponseCode > 0) {
      String response = http.getString();

      if (response == "On") {
        digitalWrite(pin, HIGH);
      } else if (response == "Off") {
        digitalWrite(pin, LOW);
      }

      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.println("Error on HTTP request");
    }

    http.end();
  }
}
    </code></pre>
    <div class="button-container">
    <button class="backButton">Back</button>
      <button class="nextButton">Next</button>
   </div>
</div>

<div class="page hidden" id="page16">
  <h1>Code Preparation (For Both INPUT and OUTPUT)</h1>
    <h3>loop()</h3>
    <pre><code>
    void loop() {
  sendSwitchData("D1", digitalRead(SWITCH_PIN1) ? "On" : "Off");
  sendSwitchData("D2", digitalRead(SWITCH_PIN2) ? "On" : "Off");
  sendSwitchData("D3", digitalRead(SWITCH_PIN3) ? "On" : "Off");
    getAndSetLEDStatus("D4", LED_PIN1);
    getAndSetLEDStatus("D5", LED_PIN2);
    getAndSetLEDStatus("D6", LED_PIN3);
    getAndSetLEDStatus("D7", LED_PIN4);
  delay(2000);
}
    </code></pre>
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
    document.getElementById('page8'),
    document.getElementById('page9'),
    document.getElementById('page10'),
    document.getElementById('page11'),
    document.getElementById('page12'),
    document.getElementById('page13'),
    document.getElementById('page14'),
    document.getElementById('page15'),
    document.getElementById('page16'),
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

