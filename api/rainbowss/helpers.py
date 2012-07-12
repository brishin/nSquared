import requests
THUMB_URL = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'

def find_thumb(urls, domain):
  params = {}
  for url in urls:
    params['image_url'] = url
    params['domain'] = domain
    thumb_request = requests.get(THUMB_URL, params=params)
    if thumb_request.status_code == 200:
      return thumb_request.content
  return None