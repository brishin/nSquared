import requests
from helpers import find_thumb
import json
from StringIO import StringIO
from PIL import Image
from pymongo import Connection, DESCENDING
import subprocess
import tempfile

connection = Connection('localhost', 27017)
db = connection.nSquared

SOLR_URL = 'http://10.10.10.31:8443/solr/select'
PAGE_LENGTH = 1000

def index_db():
  thumbs.create_index([('opedid', DESCENDING)])
  thumbs.create_index([('rgb',DESCENDING)])

def get_thumbs(domain):
  params = {}
  params['q'] = 'domain:' + domain
  params['wt'] = 'json'
  req = requests.get(SOLR_URL, params=params)
  num_thumbs = json.loads(req.content)['response']['numFound']
  params['rows'] = PAGE_LENGTH
  thumbs = {}
  progress = 0
  for i in range(num_thumbs/PAGE_LENGTH + 1):
    params['start'] = i * PAGE_LENGTH
    req = requests.get(SOLR_URL, params=params)
    data = json.loads(req.content)
    for doc in data['response']['docs']:
      if 'media' not in doc:
        break
      progress += 1
      print progress * 200 / num_thumbs
      thumbs[doc['OPEDID']] = find_thumb(doc['media'], domain)
  return thumbs

def process_thumbs(thumbs):
  palette = {}
  files = []
  out_file = tempfile.NamedTemporaryFile()
  colorific = subprocess.Popen('colorific -p 8', shell=True, bufsize=4096,
      stdin=subprocess.PIPE, stdout=out_file)
  for key, value in thumbs.iteritems():
    if value is None:
      continue
    image_request = requests.get(value)
    image = tempfile.NamedTemporaryFile(suffix=key)
    image.write(str(image_request.content))
    files.append(image) # Prevent images from being GC and deleted
    #colorific.communicate(input=image.name)
  #files = None # Now delete all
  return palette
  

def insert_thumbs(domain):
  thumbs = get_thumbs(domain)
  palette = process_thumbs(thumbs)
  for key, value in palette.iteritems():
    db.thumbs.insert({'opedid': key, 'rgb': value})