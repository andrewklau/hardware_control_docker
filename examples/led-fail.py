import wiringpi
import time

# Will fail on sudo warning
wiringpi.wiringPiSetup()

while True:
    time.sleep(0.5)
    wiringpi.digitalWrite(17,1)
    time.sleep(0.5)
    wiringpi.digitalWrite(17,0)
