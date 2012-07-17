import requests
from helpers import find_thumb
import json
from StringIO import StringIO
from PIL import Image
from pymongo import Connection, DESCENDING
import colorific
import tempfile

connection = Connection('localhost', 27017)
db = connection.nSquaredThumbs

SOLR_URL = 'http://10.10.10.31:8443/solr/select'
PAGE_LENGTH = 1000

def index_db(domain):
  db[domain].create_index([('opedid', DESCENDING)])
  db[domain].create_index([('l',DESCENDING)])
  db[domain].create_index([('a',DESCENDING)])
  db[domain].create_index([('b',DESCENDING)])

def get_thumbs(domain):
  params = {}
  params['q'] = 'domain:' + domain
  params['wt'] = 'json'
  req = requests.get(SOLR_URL, params=params)
  num_thumbs = json.loads(req.content)['response']['numFound']
  params['rows'] = PAGE_LENGTH
  thumbs = {}
  progress = 0.0
  for i in range(num_thumbs/PAGE_LENGTH + 1):
    params['start'] = i * PAGE_LENGTH
    req = requests.get(SOLR_URL, params=params, timeout=5)
    data = json.loads(req.content)
    for doc in data['response']['docs']:
      progress += 1
      if 'media' not in doc:
        continue
      print progress / num_thumbs * 100.0
      thumb_url = find_thumb(doc['media'], domain)
      if thumb_url:
        thumbs[doc['OPEDID']] = thumb_url
  return thumbs
  
def insert_thumbs(domain):
  thumbs = get_thumbs(domain)
  domain = domain.replace('.', '_')
  colorific.color_mt(thumbs.items(), domain, n=8)
  index_db(domain)