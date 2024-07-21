# HEADER#########################################################################
# ANDREW PIERSON's TENNIS BALL SPEED REGRESSION MODEL
# FILENAME: temp5.py    #do not change
# DATE: 7/20/2024
# DESCRIPTION: This python script uses glob api to measure the
#               temperature of the walk-in cooler at the shoals shack. The
#               sensor is connected to a raspberry pi that uses the request api
#               to send a text to boss.
#
# Order some food here: www.theshoalsshack.com
#
# INSTRUCTIONS##########################################
import os
import glob
import time
import requests
import random
key = '4fe42da8f34496088125c8dc4d60e7a531634374Yc0jCA3nbsDHbQRPafdlL9Glc'
phone = 3128909930

# Load Modules
os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')

# Sensor in the filesystem- source chat gpt
base_dir = '/sys/bus/w1/devices/'
device_folder = glob.glob(base_dir + '28*')[0] 
device_file = device_folder + '/w1_slave'

# read temp from device - source chat gpt
def read_temp_raw():
    with open(device_file, 'r') as f:
        lines = f.readlines()
    return lines

# Read temp and return F* and C* - source chat gpt
def read_temp():
    lines = read_temp_raw()
    while lines[0].strip()[-3:] != 'YES':
        time.sleep(0.2)
        lines = read_temp_raw()
    equals_pos = lines[1].find('t=')
    if equals_pos != -1:
        temp_string = lines[1][equals_pos+2:]
        temp_c = float(temp_string) / 1000.0
        temp_f = temp_c * 9.0 / 5.0 + 32.0
        temp_f = round(temp_f,2)
        return temp_c, temp_f

# send text - source chat gpt
def send_text(phone, key, message):
    resp = requests.post('https://textbelt.com/text', {
        'phone': phone,
        'message': message,
        'key': key,
    })
    data = resp.json()
    return data
    
#control loop
quota =0
count =0
while True:
    temp_c, temp_f = read_temp()
    print(f'Temperature: {temp_c}°C, {temp_f}°F')
    time.sleep(300) #sleeps for 5 min
    if (temp_f>=41):
        tempstr = str(temp_f)
        text = str(quota) +': Coach Bj, the walk-in is above 41*F!!!' + '\tTemp is: '+tempstr +'*F'
        data = send_text(phone, key, text)
        quota = data['quotaRemaining']
        while (temp_f>=40):
            time.sleep(300) #5 min sleep
            count = count +1
            temp_c, temp_f = read_temp()
            if (count ==4):
                tempstr3 = str(temp_f)
                text3='It has been 20min and it is:' + tempstr3 +'*F' + '\tSomething could be wrong' 
        if (temp_f<40):
            tempstr2 = str(temp_f)
            text2='The walk in is all Better now:) Temp:' + tempstr2 +'\tquota: '+quota
            data = send_text(phone, key, text2)
        print(resp.json())

