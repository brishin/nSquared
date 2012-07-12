import requests
from helpers import find_thumb

THUMB_URL = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'
SOLR_URL = 'http://10.10.10.31:8443/solr/select'

def get_thumbs_for_domain(domain):
  params = {}
  params['q'] = 'domain:' + domain
  params['wt'] = 'json'
  req = requests.get(SOLR_URL, params=params)
  num_thumbs = json.loads(req.content)
  