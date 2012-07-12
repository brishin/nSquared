def find_thumb(urls, domain):
  params = {}
  for url in urls:
    params['image_url'] = url
    params['domain'] = domain
    thumb_request = requests.get(THUMB_URL, params=params)
    if thumb_request.status_code == 200:
      return thumb_request.content
  return None