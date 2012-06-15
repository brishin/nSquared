from bs4 import BeautifulSoup
import json
import sys

if (len(sys.argv) >= 1):
  file_path = sys.argv[0]
else:
  file_path = '/Users/brian/Downloads/download.rss'

input_file = open(file_path)
soup = BeautifulSoup(input_file)

items = []
for item in soup.find_all('item'):
  temp = {}
  for child in item.children:
    try:
      temp[child.name] = child.text
    except AttributeError, e:
      pass
  items.append(temp)

output_file = open(file_path + '.json', 'w')

output_file.write(json.dumps(items))