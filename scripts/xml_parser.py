from bs4 import BeautifulSoup
import json
import sys

if (len(sys.argv) > 1):
  file_path = sys.argv[1]
else:
  file_path = '/Users/brian/Downloads/download.rss'

input_file = open(file_path)
# Most lenient parser
soup = BeautifulSoup(input_file, "html.parser")

items = []
for item in soup.find_all('item'):
  temp = {}
  temp_aggregate = {}
  for child in item.children:
    try:
      if child.name in temp:
        if child.name not in temp_aggregate:
          temp_aggregate[child.name] = [] 
        if temp[child.name] is not None:
          temp_aggregate[child.name].append(temp[child.name])
            
        temp[child.name] = None
        temp_aggregate[child.name].append(child.text)
      else:
        temp[child.name] = child.text
    except AttributeError, e:
      pass

  for tag in temp_aggregate:
      temp[tag] = temp_aggregate[tag] 

  # Finding a picture
  try:
    pics = BeautifulSoup(item.description.text).find_all('img') or []
    if (pics):
      biggest_img = max(pics, key=lambda pic: pic.get('height'))
      temp['img'] = biggest_img.get('data-lazy-src') or \
          biggest_img.get('src')
  except AttributeError, e:
    pass
  print len(temp)
  items.append(temp)

output_file = open(file_path + '.json', 'w')
print("Outputting to" + file_path + '.json')

output_file.write(json.dumps(items))
output_file.close()