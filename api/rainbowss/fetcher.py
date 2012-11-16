import requests
from helpers import find_thumb, get_domain, get_num_thumbs, get_cursor
import json
from StringIO import StringIO
from PIL import Image
from pymongo import Connection, DESCENDING
import colorific
import tempfile
import sunburnt
import redis
from datetime import datetime
from schema import Site, Thumb

class Fetcher:
  '''
  Implementation of much of the work done on thumbnails.
  '''
  def __init__(self):
    self.connection = Connection('localhost', 27017)
    self.db = self.connection.nSquared
    self.COLLECTION = 'thumbs'
    self.r = redis.StrictRedis(host='localhost', port=6379, db=0)

    self.SOLR_URL = 'http://10.10.10.31:8443/solr/'
    self.solr = sunburnt.SolrInterface(self.SOLR_URL)
    self.PAGE_LENGTH = 1000
    self.cursor = get_cursor()

  def index_db(self):
    self.db[self.COLLECTION].create_index([('opedid', DESCENDING)])
    self.db[self.COLLECTION].create_index([('l',DESCENDING)])
    self.db[self.COLLECTION].create_index([('a',DESCENDING)])
    self.db[self.COLLECTION].create_index([('b',DESCENDING)])
    self.db[self.COLLECTION].create_index([('rssid', DESCENDING)])

  def get_thumbs(self, rssid, domain, last_updated=None):
    num_thumbs = get_num_thumbs(rssid)
    thumbs = {}
    for start in xrange(0, num_thumbs, self.PAGE_LENGTH):
      response = self.solr.query().filter(rssid=rssid).paginate(start=start,
          rows=self.PAGE_LENGTH)
      if last_updated:
        response = response.query(timestamp__gte=last_updated)
      response = response.execute()
      for doc in response:
        if 'media' not in doc:
          continue
        thumb_url = find_thumb(doc['media'], domain)
        if thumb_url:
          thumbs[doc['OPEDID']] = thumb_url
    return thumbs

  def clear_cache(self, rssid):
    keys = self.r.keys("%s_*" % str(rssid))
    if keys:
      self.r.delete(*keys)

  # Removes all existing rssids.
  def insert_thumbs(self, rssid):
    self.db[self.COLLECTION].remove({'rssid': rssid}, safe=True)
    Site.objects(rssid=rssid).delete()
    domain = get_domain(rssid, cursor=self.cursor)
    site = Site(rssid=rssid, domain=domain)
    thumbs = get_thumbs(rssid, domain)
    try:
      colorific.color_mt(thumbs.items(), rssid, n=8)
    except Exception, e:
      raise e
    else:
      clear_cache(rssid)
      site.last_updated = datetime.now()
      site.save()
    # index_db()

  def update_thumbs(self, rssid):
    domain = get_domain(rssid)
    site, created = Site.objects.get_or_create(rssid=rssid,
        defaults={'domain': domain})
    if created:
      last_updated = None
    else:
      last_updated = site.last_updated
    thumbs = get_thumbs(rssid, domain, last_updated=last_updated)
    try:
      colorific.color_mt(thumbs.items(), rssid, n=8)
    except Exception,  e:
      raise e
    else:
      site.last_updated = datetime.now()
      site.save()