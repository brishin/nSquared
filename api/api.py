from flask import Flask, request, abort, current_app
from functools import wraps
import json, requests
from rainbowss.helpers import find_thumb

from pymongo import Connection
from colormath.color_objects import RGBColor, LabColor
import operator

app = Flask(__name__)
app.config['solr_url'] = 'http://10.10.10.31:8443/solr/select'
# Whitelist of parameters allowed to send to solr
app.config['allowed_params'] = ['rows', 'start']
app.config['THUMB_URL'] = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'

connection = Connection('localhost', 27017)
db = connection.nSquaredThumbs
COLOR_SENSITIVITY = 5
PROMINENCE_WEIGHT = 0.2
MAX_COLOR_RESULTS = 20

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
    solr_request = requests.get(app.config['solr_url'], params=params, timeout=5)
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
    params['q'] = params['q'] + 'AND' + request.args['search']
    solr_request = requests.get(app.config['solr_url'], params=params, timeout=5)
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

@app.route('/v1/color', methods=['GET'])
@jsonp
def color_api(color_hex=None, domain=None):
  if 'color' not in request.args and 'domain' not in request.args\
      and color_hex is None and domain is None:
    abort(404)
  color = RGBColor()
  if color_hex is None:
    domain = request.args['domain'].replace('.', '_')
    color.set_from_rgb_hex(request.args['color'])
  else:
    domain = domain.replace('.', '_')
    color.set_from_rgb_hex(color_hex)
  color = color.convert_to('lab')
  l = color.get_value_tuple()[0]
  a = color.get_value_tuple()[1]
  b = color.get_value_tuple()[2]
  d = COLOR_SENSITIVITY
  query = {'$and': [{'l': {'$lte': l+d, '$gte': l-d}}, {'a': {'$lte': a+d, '$gte': a-d}}, {'b': {'$lte': a+d, '$gte': a-d}}]}
  cursor = db[domain].find(query)
  colors = mongo_to_colors(cursor)
  results = find_closest(color, colors)

  params = build_params(request.args)
  params['q'] = ''
  results = results[:MAX_COLOR_RESULTS]
  if len(results) == 0:
    return json.dumps([])
  for result in results:
    params['q'] = params['q'] + 'OPEDID:' + str(result[0]) + ' OR '
  params['q'] = params['q'][:-4] #Remove trailing AND
  solr_request = requests.get(app.config['solr_url'], params=params, timeout=5)
  data = json.loads(solr_request.content)
  if 'response' in data and 'docs' in data['response']:
    solr_results = data['response']['docs']
    fetch_thumb_requests(request, solr_results, color_results=results)
    return json.dumps(solr_results)
  abort(404)

def mongo_to_colors(cursor):
  'Rotates the mongo data for python use, returning a hash of similar information'
  colors = {}
  for doc in cursor:
    palette = []
    labs = []
    labs.append(doc['l'])
    labs.append(doc['a'])
    labs.append(doc['b'])
    labs = zip(*labs)
    for lab in labs:
      lab_color = LabColor(*lab)
      palette.append(lab_color)
    colors[str(doc['opedid'])] = {}
    colors[str(doc['opedid'])]['palette'] = palette
    colors[str(doc['opedid'])]['prominence'] = doc['prominence']
    colors[str(doc['opedid'])]['t_url'] = doc['t_url']
  return colors

def find_closest(target, colors):
  'Returns an array of tuples (opedid, score, url) sorted by score'
  thumb_scores = []
  for key, value in colors.iteritems():
    scores = []
    for i, color in enumerate(value['palette']):
      prominence_factor = PROMINENCE_WEIGHT * value['prominence'][i]
      scores.append(target.delta_e(color) * prominence_factor)
    thumb_scores.append((key, max(scores), value['t_url']))
  thumb_scores = sorted(thumb_scores, key=operator.itemgetter(1),
    reverse=True)
  return thumb_scores

def fetch_thumb_requests(request, results, color_results=None):
  for i, result in enumerate(results):
    if 'media' not in result:
      app.logger.debug('media not in result')
      continue
    if color_results is not None:
      result['thumb_request'] = color_results[i][2]
    else:
      result['thumb_request'] = find_thumb(result['media'], request.args['domain'])

def build_params(args):
  params = {}
  params['wt'] = 'json'
  if 'domain' in args:
    params['q'] = 'domain:' + args.get('domain')
  for key in args.keys():
    if key in app.config['allowed_params']:
      params[key] = args.get(key)
  return params

if __name__ == '__main__':
  app.run(debug=True, debug=8000)