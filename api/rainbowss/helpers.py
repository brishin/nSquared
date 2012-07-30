import requests
import sunburnt

THUMB_URL = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'
SOLR_URL = 'http://10.10.10.31:8443/solr/'
solr = sunburnt.SolrInterface(SOLR_URL)

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
  response = solr.filter(rssid=rssid).paginate(rows=1).execute()
  if response.status is not 0:
    return None
  docs = list(response)
  if len(docs) > 0:
    return docs[0].get('domain', None)

def get_rssid(domain):
  response = solr.filter(domain=domain).paginate(rows=1).execute()
  if response.status is not 0:
    return None
  docs = list(response)
  if len(docs) > 0:
    return docs[0].get('rssid', None)

def get_num_thumbs(rssid):
  response = solr.filter(rssid=rssid).paginate(rows=1).execute()
  if response.status is not 0:
    return 0
  return response.result.numFound