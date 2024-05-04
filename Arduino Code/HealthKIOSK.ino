#include <Wire.h>
#include "MAX30105.h"
#include "heartRate.h"
#include "spo2_algorithm.h"
#include <WiFi.h>
#include <MySQL_Generic.h>
MAX30105 particleSensor;
boolean startflag = 0, continueflag = 0;

const char* ssid = "realme9";
const char* psk  = "amal@2001";

IPAddress server(192,168,240,126);
uint16_t server_port = 3306;

char user[] = "health1_user";
char password[] = "123456789";

char default_database[] = "muthoot_health";
char default_table[] = "health_tb";


char query[128];
char INSERT_DATA[] = "INSERT INTO %s.%s (O2, bpm,temperature) VALUES ('%s', '%s', '%s')";

MySQL_Connection conn((Client*)&client);

void dbConnect(String O2, String bpm, String temperature) {
  MySQL_Query query_mem = MySQL_Query(&conn);

  Serial.println(String(O2) + ", " + String(bpm)+ ", " + String(temperature));
  if (conn.connected()) {
    sprintf(query, INSERT_DATA, default_database, default_table, String(O2).c_str(), String(bpm).c_str(), String(temperature).c_str());
    Serial.println(query);

    if (!query_mem.execute(query)) {
      Serial.println("Update/Insert error");
    } else {
      Serial.println("Data Updated/Inserted");
    }
  } else {
    Serial.println("Disconnected from Server. Can't Update/Insert.");
  }

}
uint32_t irBuffer[100];
uint32_t redBuffer[100];

int32_t bufferLength;
int32_t spo2;
int8_t validSPO2;
int32_t heartRate;
int8_t validHeartRate;
float temperatureF = 0;

void setup() {
  Serial.begin(9600);
  WiFi.begin(ssid, psk);
  Serial.print("Connecting to Wi-Fi");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(300);
  }
  Serial.println();
  Serial.print("Connected with IP: ");
  Serial.println(WiFi.localIP());
  Serial.println();
  if (!particleSensor.begin(Wire, I2C_SPEED_FAST)) { 
    Serial.println ("Sensor connection failure");
    while (1) {
      delay (10);
    }
  }
    WiFi.begin(ssid, psk);
  while (WiFi.status() != WL_CONNECTED) {
    delay(20);
    MYSQL_DISPLAY0(".");
  }
  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());

  MYSQL_DISPLAY1("Connected to network. My IP address is:", WiFi.localIP());
  MYSQL_DISPLAY3("Connecting to SQL Server @", server, ", Port =", server_port);
  MYSQL_DISPLAY5("User =", user, ", PW =", password, ", DB =", default_database);
  delay(700);          /* Start the DHT11 Sensor */
}

void loop() {
  boolean processFlag = true;
  const byte RATE_SIZE = 10;
  byte rates[RATE_SIZE];
  byte avg_rates[RATE_SIZE];
  long del[25], timeDelay;
  byte rateSpot = 0, avgCnt = 0, loopCnt = 0;
  long lastBeat = 0; 
  float beatsPerMinute;
  int beatAvg, Avg_beat, HRbpm;
  
  particleSensor.setup();
  particleSensor.setPulseAmplitudeRed(0x0A);
  particleSensor.setPulseAmplitudeGreen(0); 
  
  Serial.println("Make proper Contact with sensor");
  delay (1500);
  if (processFlag == true) {
    long irValue = particleSensor.getIR();
    if (irValue > 50000) {
      startflag = true;
      continueflag = true;
      
      Serial.println("Contact detected");
      delay (1500);
    }
    
    Serial.print("Calibrating HR, ");
    while (startflag == true) {
      long irValue = particleSensor.getIR();
      if (irValue > 50000) {
        if (checkForBeat(irValue) == true) {
          long delta = millis() - lastBeat;
          lastBeat = millis();
          
          beatsPerMinute = 60 / (delta / 1000.0);
          if (beatsPerMinute < 255 && beatsPerMinute > 20) {
            rates[rateSpot++] = (byte)beatsPerMinute;
            rateSpot %= RATE_SIZE;
            beatAvg = 0;
            for (byte x = 0 ; x < RATE_SIZE ; x++)
              beatAvg += rates[x];
            beatAvg /= RATE_SIZE;
              
            avgCnt++;
            del[avgCnt] = delta;
            
            String progress = String (map (avgCnt, 0, 25, 0, 100));
            // Serial.println ("*" + progress + "#");
            Serial.println(progress + "%");

            if (avgCnt >= 11 && avgCnt <= 20) {
              avg_rates[loopCnt++] = (byte)beatAvg;
              loopCnt %= RATE_SIZE;
            }
            else {
              for (byte y = 0 ; y < RATE_SIZE ; y++)
                Avg_beat += avg_rates[y];
              Avg_beat /= RATE_SIZE;
            }
            if (avgCnt >= 25) {
              HRbpm = Avg_beat;
              startflag = false;
              processFlag = false;
              avgCnt = 0;
              delay (100);
            }
          }
        }
      }
    }
  }

  if (continueflag == true) {
    processFlag = true;
    byte ledBrightness  = 60;
    byte sampleAverage  = 4;
    byte ledMode        = 2;
    byte sampleRate     = 100;
    int pulseWidth      = 411;
    int adcRange        = 4096;

    particleSensor.setup(ledBrightness, sampleAverage, ledMode, sampleRate, pulseWidth, adcRange);
    Serial.print("Calibrating SPO2");
    while (processFlag == true) {
      bufferLength = 100;
      
      for (byte i = 0 ; i < bufferLength ; i++) {
        while (particleSensor.available() == false) 
          particleSensor.check();
    
        redBuffer[i] = particleSensor.getRed();
        irBuffer[i] = particleSensor.getIR();
        particleSensor.nextSample();
      }
      maxim_heart_rate_and_oxygen_saturation(irBuffer, bufferLength, redBuffer, &spo2, &validSPO2, &heartRate, &validHeartRate);
      startflag = true;

      avgCnt = 0;
      while (startflag == true) {
        for (byte i = 25; i < 100; i++) {
          redBuffer[i - 25] = redBuffer[i];
          irBuffer[i - 25] = irBuffer[i];
        }

        for (byte i = 75; i < 100; i++) {
          while (particleSensor.available() == false)
            particleSensor.check();
    
          redBuffer[i] = particleSensor.getRed();
          irBuffer[i] = particleSensor.getIR();
          particleSensor.nextSample();

          avgCnt ++;
          if (validSPO2 == 1) {
            String progress = String (map (avgCnt, 0, 50, 0, 100));
            Serial.println (progress + "%");
            if (avgCnt ==  50) {
              startflag = 0;
              processFlag = 0;
              delay (500);
            }
          }
          else {
            avgCnt = 0;
          }
        }
        maxim_heart_rate_and_oxygen_saturation(irBuffer, bufferLength, redBuffer, &spo2, &validSPO2, &heartRate, &validHeartRate);
      }
    }

    
    Serial.print("Calibrating for Temperature");
    processFlag = true;
    particleSensor.setup(0);
    particleSensor.enableDIETEMPRDY();
    delay (1500);
    
    temperatureF = 0;
    while (processFlag == true) {
      for (int dl = 0; dl <= 30; dl++) {
        String progress = String (map (dl, 0, 30, 0, 100));
        Serial.println (progress + "%");
        temperatureF += particleSensor.readTemperatureF();
        delay (100);
      }
      temperatureF = temperatureF / 30;
      processFlag = false;
      delay (1500);
    }

    Serial.println ("Heart Rate: " + String (HRbpm) + " bpm");
    Serial.println ("Oxygen Saturation: " + String (spo2) + " %");
    Serial.println ("Temperature: " + String (temperatureF) + " F");
    continueflag = false;

    MYSQL_DISPLAY("Connecting...");
    if (conn.connectNonBlocking(server, server_port, user, password) != RESULT_FAIL) {
      delay(1000);

      dbConnect(String (spo2) , String (HRbpm),String (temperatureF));
   
      conn.close();

    } else {
      MYSQL_DISPLAY("\nConnect failed. Trying again on the next iteration.");
    }
  }
  

  delay(1000); 
}