import re
try:
    import xml.etree.cElementTree as ET
except ImportError:
    import xml.etree.ElementTree as ET

f = open("testXML.xml", 'r')
print f

while True:
  currentline = f.readline()
  if len(currentline) > 1:
    # print currentline
    if re.search("hist", currentline):
      # print 'HIST'
      pass
    else:
      root = ET.fromstring(currentline)
      time = root.find('time').text
      sensor = root.find('sensor').text
      for ch1 in root.findall('ch1'):
        watts = ch1.find('watts').text
      if sensor == '0':
        tmrp = root.find('tmpr').text
        print time, sensor, watts, tmrp
      else:
        print time, sensor, watts







  # print root.tag
  # print root.attrib

  # for child in root:
  #   print child.tag, child.attrib, child.text

  # for ch1 in root.findall('ch1'):
  #   watts = ch1.find('watts').text
  #   print watts