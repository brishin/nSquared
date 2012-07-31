import requests
from helpers import find_thumb, get_domain, get_num_thumbs
import json
from StringIO import StringIO
from PIL import Image
from pymongo import Connection, DESCENDING
import colorific
import tempfile
import sunburnt

connection = Connection('localhost', 27017)
db = connection.nSquared
COLLECTION = 'thumbs'

SOLR_URL = 'http://10.10.10.31:8443/solr/'
solr = sunburnt.SolrInterface(SOLR_URL)
PAGE_LENGTH = 1000

def index_db():
  db[COLLECTION].create_index([('opedid', DESCENDING)])
  db[COLLECTION].create_index([('l',DESCENDING)])
  db[COLLECTION].create_index([('a',DESCENDING)])
  db[COLLECTION].create_index([('b',DESCENDING)])
  db[COLLECTION].create_index([('rssid', DESCENDING)])

def get_thumbs(rssid, domain, last_updated=None):
  num_thumbs = get_num_thumbs(rssid)
  rows = PAGE_LENGTH
  thumbs = {}
  progress = 0.0
  for i in range(num_thumbs/PAGE_LENGTH + 1):
    start = i * PAGE_LENGTH
    response = solr.query().filter(rssid=rssid).paginate(start=start, rows=rows)
    if last_updated:
      response = response.query(timestamp__gte=last_updated)
    response = response.execute()
    for doc in response:
      progress += 1
      if 'media' not in doc:
        continue
      print progress / num_thumbs * 100.0
      thumb_url = find_thumb(doc['media'], domain)
      if thumb_url:
        thumbs[doc['OPEDID']] = thumb_url
  return thumbs
  
def insert_thumbs(rssid):
  domain = get_domain(rssid)
  thumbs = get_thumbs(rssid, domain)
  colorific.color_mt(thumbs.items(), rssid, n=8)
  index_db()

def update_thumbs(rssid, last_updated):
  domain = get_domain(rssid)
  thumbs = get_thumbs(rssid, domain, last_updated=last_updated)
  colorific.color_mt(thumbs.items(), rssid, n=8)