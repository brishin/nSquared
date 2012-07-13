import requests
from helpers import find_thumb
import json

SOLR_URL = 'http://10.10.10.31:8443/solr/select'
PAGE_LENGTH = 1000

def get_thumbs_for_domain(domain):
  params = {}
  params['q'] = 'domain:' + domain
  params['wt'] = 'json'
  req = requests.get(SOLR_URL, params=params)
  num_thumbs = json.loads(req.content)['response']['numFound']
  params['rows'] = PAGE_LENGTH
  thumbs = {}
  for i in range(num_thumbs/PAGE_LENGTH + 1):
    params['start'] = i * PAGE_LENGTH
    req = requests.get(SOLR_URL, params=params)
    data = json.loads(req.content)
    for doc in data['response']['docs']:
      thumbs[doc['OPEDID']] = find_thumb(doc['media'], domain)
  return thumbs