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

def get_domain(rssid, connection=None):
  query = "SELECT keyCode FROM `domains` WHERE rssid = %s", str(rssid)
  domain = execute_fetchone(connection, query)
  if domain and len(domain) > 0:
    domain = domain[0]
  return domain

def get_rssid(domain, connection=None):
  query = "SELECT rssid FROM `domains` WHERE keyCode = %s", str(domain)
  rssid = execute_fetchone(connection, query)
  if rssid and len(rssid) > 0:
    rssid = rssid[0]
  return rssid

def execute_fetchone(connection, query):
  try:
    if connection is None:
      (connection, cursor) = connect_mysql()
    else:
      cursor = connection.cursor()
    cursor.execute(str(query))
    return cursor.fetchone() or None
  except Exception, e:
    connection.close()
    raise

def get_num_thumbs(rssid):
  response = solr.query().filter(rssid=rssid).paginate(rows=1).execute()
  if response.status is not 0:
    return 0
  return response.result.numFound

def connect_mysql():
  connection = MySQLdb.connect(*MYSQL_SETTINGS)
  cursor = connection.cursor()
  return connection, cursor