import requests
import sunburnt
import MySQLdb

THUMB_URL = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'
SOLR_URL = 'http://10.10.10.31:8443/solr/'
solr = sunburnt.SolrInterface(SOLR_URL)
MYSQL_SETTINGS = '10.10.10.17', 'vulcan', '', 'linksDBProd'

def find_thumb(urls, domain):
  params = {}
  for url in urls:
    params['image_url'] = url
    params['domain'] = domain
    thumb_request = requests.get(THUMB_URL, params=params)
    if thumb_request.status_code == 200:
      return thumb_request.content
  return None

def get_domain(rssid):
  connection = None
  domain = None
  try:
    (connection, cursor) = connect_mysql()
    cursor.execute("SELECT keyCode FROM `domains` WHERE rssid = %s", str(rssid))
    domain = cursor.fetchone()
    if domain and len(domain) > 0:
      domain = domain[0]
  except Exception, e:
    raise e
  finally:
    if connection:
      connection.close()
  return domain

def get_rssid(domain):
  connection = None
  rssid = None
  try:
    (connection, cursor) = connect_mysql()
    cursor.execute("SELECT rssid FROM `domains` WHERE keyCode = %s", str(domain))
    rssid = cursor.fetchone()
    if rssid and len(rssid) > 0:
      rssid = rssid[0]
  except Exception, e:
    raise e
  finally:
    if connection:
      connection.close()
  return rssid
    

def get_num_thumbs(rssid):
  response = solr.query().filter(rssid=rssid).paginate(rows=1).execute()
  if response.status is not 0:
    return 0
  return response.result.numFound

def connect_mysql():
  connection = MySQLdb.connect(*MYSQL_SETTINGS)
  cursor = connection.cursor()
  return connection, cursor