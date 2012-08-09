import json
import os

callback = 'weqrio9834938'

if __name__ == '__main__':
  directory = os.getcwd()
  for root,dirs,files in os.walk(directory):
    for dir_file in files:
      if dir_file.endswith('.html'):
        f = open(dir_file, 'r')
        out = ''
        for line in f:
          out += line.strip()
        json_out = json.dumps(out)
        output_file = open(dir_file + '.json', 'w')
        output_file.write("%s(%s)" % (callback, json_out))
        f.close()
        output_file.close()