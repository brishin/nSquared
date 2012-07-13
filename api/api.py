from flask import Flask, request, abort, current_app
from functools import wraps
import json, requests
from rainbowss.helpers import find_thumb

app = Flask(__name__)
app.config['solr_url'] = 'http://10.10.10.31:8443/solr/select'
# Whitelist of parameters allowed to send to solr
app.config['allowed_params'] = ['rows', 'start']
app.config['THUMB_URL'] = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'

def jsonp(func):
  """Wraps JSONified output for JSONP requests."""
  @wraps(func)
  def decorated_function(*args, **kwargs):
    callback = request.args.get('callback', False)
    if callback:
      data = str(func(*args, **kwargs))
      content = str(callback) + '(' + data + ')'
      mimetype = 'application/javascript'
      return current_app.response_class(content, mimetype=mimetype)
    else:
      return func(*args, **kwargs)
  return decorated_function

@app.route('/v1/posts', methods=['GET'])
@jsonp
def posts_api():
  solr_request = None
  if 'domain' in request.args:
    params = build_params(request.args)
    solr_request = requests.get(app.config['solr_url'], params=params)
    data = json.loads(solr_request.content)
    if 'response' in data and 'docs' in data['response']:
      results = data['response']['docs']
      fetch_thumb_requests(request, results)
      return json.dumps(results)

  if solr_request is not None:
    abort(solr_request.status_code)
  else:
    abort(404)

@app.route('/v1/search', methods=['GET'])
@jsonp
def search_api():
  solr_request = None
  if 'domain' in request.args and 'search' in request.args:
    params = build_params(request.args)
    # Solr query
    params['q'] = params['q'] + '\n' + request.args['search']
    solr_request = requests.get(app.config['solr_url'], params=params)
    app.logger.debug('GET(search) ' + solr_request.url)

    data = json.loads(solr_request.content)
    if 'response' in data and 'docs' in data['response']:
      results = data['response']['docs']
      fetch_thumb_requests(request, results)
      return json.dumps(results)

  if solr_request is not None:
    abort(solr_request.status_code)
  else:
    abort(404)

def fetch_thumb_requests(request, results):
  for result in results:
    if 'media' not in result:
      app.logger.debug('media not in result')
      break
    app.logger.debug(find_thumb(result['media'], request.args['domain']))
    result['thumb_request'] = find_thumb(result['media'], request.args['domain'])

def build_params(args):
  params = {}
  params['wt'] = 'json'
  params['q'] = 'domain:' + args.get('domain')
  for key in args.keys():
    if key in app.config['allowed_params']:
      params[key] = args.get(key)
  return params

if __name__ == '__main__':
  app.run(debug=True)