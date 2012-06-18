import json
import sys
import xmltodict

if (len(sys.argv) > 1):
  file_path = sys.argv[1]
else:
  file_path = '/Users/brian/Downloads/download.rss'

input_file = open(file_path)

doc = xmltodict.parse(input_file)

output_file = open(file_path + '.json', 'w')
print("Outputting to" + file_path + '.json')

output_file.write(json.dumps(doc))