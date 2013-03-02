import settings
import serial
import time
# XML parser
try:
    import xml.etree.cElementTree as ET
except ImportError:
    import xml.etree.ElementTree as ET
# regex
import re
import MySQLdb

# installation settings
monitor_id = '33_bs66nj_0'

# database settings - AWS
db = MySQLdb.connect(host=settings.db_host,
                      user=settings.db_user,
                      passwd=settings.db_passwd,
                      db=settings.db_database)
print 'Connected RDS'

# create cursor for db
#cur = db.cursor()

# serial port settings
ser = serial.Serial('com4',57600,timeout=1)
# print ser.portstr

while True:
  # read from serial port
  currentline = ser.readline(9999)
  # check something is received
  if len(currentline) > 0:
    # check to see if line has historical data
    if re.search("hist", currentline):
      # do nothing, not logging hist values
      pass
    else:
      # parse XML
      root = ET.fromstring(currentline)
      monitor_time = root.find('time').text
      sensor = root.find('sensor').text
      for ch1 in root.findall('ch1'):
        watts = ch1.find('watts').text

      # check to see if monitor or IAM
      if sensor == '0':
        # if monitor read temperature from XML
        tmpr = root.find('tmpr').text

        # insert temperature reading
        datetime = time.strftime('%Y-%m-%d %X')
        status = 0
        sensor_type = 0 # 0=>tmpr, 1=>elec
        #cur.execute("""INSERT INTO 33_bs66nj (datetime, sensor_id,sensor_type,sensor_value,monitor_id,monitor_time,status) VALUES (%s,%s,%s,%s,%s,%s,%s)""",(datetime,sensor,sensor_type,tmpr,monitor_id,monitor_time,status))
        #db.commit()

        # insert elec(clamp) reading
        datetime = time.strftime('%Y-%m-%d %X')
        status = 0
        sensor_type = 1 # 0=>tmpr, 1=>elec
        #cur.execute("""INSERT INTO 33_bs66nj (datetime, sensor_id,sensor_type,sensor_value,monitor_id,monitor_time,status) VALUES (%s,%s,%s,%s,%s,%s,%s)""",(datetime,sensor,sensor_type,watts,monitor_id,monitor_time,status))
        #db.commit()

        # print to screen
        print time.ctime(), monitor_time, int(sensor), int(watts), float(tmpr)
      
      else:
        # insert elec(IAM) reading
        datetime = time.strftime('%Y-%m-%d %X')
        status = 0
        sensor_type = 1 # 0=>tmpr, 1=>elec
        #cur.execute("""INSERT INTO 33_bs66nj (datetime, sensor_id,sensor_type,sensor_value,monitor_id,monitor_time,status) VALUES (%s,%s,%s,%s,%s,%s,%s)""",(datetime,sensor,sensor_type,watts,monitor_id,monitor_time,status))
        #db.commit()

        # print to screen
        print time.ctime(), monitor_time, int(sensor), int(watts)
        
  time.sleep(0.5)
  print 'not blocked'

ser.close()
