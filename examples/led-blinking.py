import wiringpi
import time

wiringpi.wiringPiSetup()

while True:
    time.sleep(0.5)
    wiringpi.digitalWrite(0,1)
    wiringpi.digitalWrite(1,0)
    time.sleep(0.5)
    wiringpi.digitalWrite(0,0)
    wiringpi.digitalWrite(1,0)
