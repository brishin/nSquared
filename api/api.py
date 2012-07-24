from flask import Flask, request, abort, current_app
from functools import wraps
import json, requests
from rainbowss.helpers import find_thumb

from pymongo import Connection
from colormath.color_objects import RGBColor, LabColor
import operator
import sunburnt

app = Flask(__name__)
app.config['THUMB_URL'] = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'

connection = Connection('localhost', 27017)
db = connection.nSquared
COLLECTION = 'thumbs'
SOLR_URL = 'http://10.10.10.31:8443/solr/'
solr = sunburnt.SolrInterface(SOLR_URL)

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
  response = query_solr({}, request.args)
  fetch_thumb_requests(response, request.args)
  return response_to_json(response)

@app.route('/v1/search', methods=['GET'])
@jsonp
def search_api():
  if 'search' not in request.args:
    abort(400)
  response = query_solr(request.args['search'], request.args, sort='-score')
  fetch_thumb_requests(response, request.args)
  return response_to_json(response)

@app.route('/v1/color', methods=['GET'])
@jsonp
def color_api():
  if 'rssid' not in request.args or 'color' not in request.args:
    abort(400)
  color = RGBColor()
  color.set_from_rgb_hex('#' + request.args['color'])
  color = color.convert_to('lab')
  l = color.get_value_tuple()[0]
  a = color.get_value_tuple()[1]
  b = color.get_value_tuple()[2]
  d = COLOR_SENSITIVITY
  query = {'$and': [{'rssid': str(rssid)}, {'l': {'$lte': l+d, '$gte': l-d}}\
      , {'a': {'$lte': a+d, '$gte': a-d}}, {'b': {'$lte': b+d, '$gte': b-d}}]}
  cursor = db[COLLECTION].find(query)
  colors = mongo_to_colors(cursor)
  results = find_closest(color, colors)

  if 'debug' in request.args:
    results = find_closest_debug(color, colors)
    return json.dumps(results)

  results = results[:MAX_COLOR_RESULTS]
  if len(results) == 0:
    return json.dumps([])
  response = query_solr({}, request.args, return_raw=True, rows=MAX_COLOR_RESULTS)
  query = solr.Q()
  for result in results:
    query |= solr.Q(OPEDID=str(result[0]))
  response = response.query(query).execute()
  app.logger.debug([response.params, response.status])
  response = list(response)
  fetch_thumb_requests(response, request.args)
  ordered_response = []
  for result in results:
    try:
      matching_element = next(x for x in response if x['OPEDID'] == str(result[0]))
      response.remove(matching_element)
      ordered_response.append(matching_element)
    except StopIteration:
      continue
  return response_to_json(ordered_response)

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
      scores.append(target.delta_e(color) * (1 + prominence_factor))
    thumb_scores.append((key, max(scores), value['t_url']))
  thumb_scores = sorted(thumb_scores, key=operator.itemgetter(1),
    reverse=True)
  return thumb_scores

def find_closest_debug(target, colors):
  'Returns an array of tuples (opedid, score, url) sorted by score'
  thumb_scores = []
  for key, value in colors.iteritems():
    scores = []
    for i, color in enumerate(value['palette']):
      prominence_factor = PROMINENCE_WEIGHT * value['prominence'][i]
      scores.append(target.delta_e(color) * (1 + prominence_factor))
    max_score = max(scores)
    max_color = str(value['palette'][scores.index(max_score)])
    thumb_scores.append((key, max_score, value['t_url'], max_color))
  thumb_scores = sorted(thumb_scores, key=operator.itemgetter(1),
    reverse=True)
  for i in range(len(thumb_scores)):
    thumb_scores[i] = list(thumb_scores[i])
    doc = db['trendland_com'].find_one({'opedid': thumb_scores[i][0]})
    del doc['_id']
    thumb_scores[i].append(doc)
  thumb_scores.insert(0, {'target': str(target)})
  return thumb_scores

def fetch_thumb_requests(solr_response, rargs):
  for result in solr_response:
    result['thumb_request'] = find_thumb(result['media'], rargs['domain'])

def query_solr(query, rargs, sort="-datetime", return_raw=False, **kwargs):
  pagination = {}
  fq = {}
  if 'rssid' not in rargs or rargs.get('rssid') == '':
    #abort(400)
    fq['rssid'] = 6084639 #Debug
  else:
    fq['rssid'] = rargs['rssid']
  pagination['start'] = kwargs.get('start') or rargs.get('start')
  pagination['rows'] = kwargs.get('rows') or rargs.get('rows')

  if isinstance(query, dict):
    response = solr.query(**query)
  else:
    response = solr.query(query) # Query is a string

  response = response.filter(**fq).paginate(**pagination).sort_by(sort)
  if return_raw:
    return response
  response = response.execute()
  app.logger.debug([response.params, response.status])
  if response.status is not 0:
    abort(400)
  return list(response)

def response_to_json(response):
  if not isinstance(response, list):
    response = list(response)
  output = json.dumps(response, default=dthandler)
  return output

def dthandler(obj):
  if hasattr(obj, 'isoformat'):
    return obj.isoformat()
  else:
    return obj

if __name__ == '__main__':
  app.run(debug=True, port=8000)