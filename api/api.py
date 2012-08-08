from flask import Flask, request, abort, current_app
from functools import wraps
import json, requests
from rainbowss.helpers import find_thumb, get_rssid, get_domain

from pymongo import Connection
from colormath.color_objects import RGBColor, LabColor
import operator
import sunburnt
import hashlib, redis, pickle

app = Flask(__name__)
app.config['THUMB_URL'] = 'http://209.17.190.27/rcw_wp/0.51.0/cache_image_lookup.php'

COLLECTION = 'thumbs'
SOLR_URL = 'http://10.10.10.31:8443/solr/'
COLOR_SENSITIVITY = 5
PROMINENCE_WEIGHT = 0.2
MAX_COLOR_RESULTS = 30

connection = Connection('localhost', 27017)
db = connection.nSquared
r = redis.StrictRedis(host='localhost', port=6379, db=0)
solr = sunburnt.SolrInterface(SOLR_URL)

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
  opeds = None
  try:
    query = json.loads(request.args['search'])
    color_item = [x for x in query if 'color' in x]
    if color_item:
      query.remove(color_item[0])
      rgb_hex = color_item[0]['color']
      opeds = find_color_opeds(rgb_hex)
      app.logger.debug('Matching opeds for color: ' + str(opeds))
      if len(opeds) == 0:
        return json.dumps([])
  except ValueError, e:
    query = request.args['search']
  response = query_solr(query, request.args, sort='-score', opeds=opeds)
  fetch_thumb_requests(response, request.args)
  if opeds:
    response = order_response(response, opeds)
  return response_to_json(response)

def find_color_opeds(rgb_hex):
  'Returns matching opeds for a given color'
  color = RGBColor()
  color.set_from_rgb_hex('#' + rgb_hex)
  cursor = db_find(color)
  colors = mongo_to_colors(cursor)
  results = find_closest(color, colors)[:MAX_COLOR_RESULTS]
  opeds = [result[0] for result in results]
  return opeds

def order_response(results, opeds):
  'Reorders a solr result list by the order of a oped list.'
  results = list(results)
  ordered_response = []
  for oped in opeds:
    try:
      matching_element = next(x for x in results if x.get('OPEDID') == oped)
      results.remove(matching_element)
      ordered_response.append(matching_element)
    except StopIteration:
      continue
  return ordered_response

def db_find(color):
  'Finds docs in the database close to a given RGB color.'
  color = color.convert_to('lab')
  l = color.get_value_tuple()[0]
  a = color.get_value_tuple()[1]
  b = color.get_value_tuple()[2]
  d = COLOR_SENSITIVITY
  rssid = request.args['rssid']
  query = {'$and': [{'rssid': str(rssid)}, {'l': {'$lte': l+d, '$gte': l-d}}\
      , {'a': {'$lte': a+d, '$gte': a-d}}, {'b': {'$lte': b+d, '$gte': b-d}}]}
  cursor = db[COLLECTION].find(query)
  return cursor

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
    colors[str(doc['opedid'])]['url'] = doc['url']
  return colors

def find_closest(target, colors):
  'Returns an array of tuples (opedid, score, url) sorted by score'
  thumb_scores = []
  for key, value in colors.iteritems():
    scores = []
    for i, color in enumerate(value['palette']):
      prominence_factor = PROMINENCE_WEIGHT * value['prominence'][i]
      scores.append(target.delta_e(color) * (1 + prominence_factor))
    thumb_scores.append((key, max(scores), value['url']))
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
    thumb_scores.append((key, max_score, value['url'], max_color))
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
    if 'media' not in result:
      continue
    result['thumb_request'] = find_thumb(result['media'], rargs['domain'])

def query_solr(query, rargs, sort="-datetime", opeds=None, **kwargs):
  pagination = {}
  fq = {}
  if not rargs.get('rssid'):
    if 'domain' in rargs:
      fq['rssid'] = get_rssid(rargs.get('domain'))
    else:
      abort(400)
  else:
    fq['rssid'] = rargs['rssid']
  pagination['start'] = kwargs.get('start') or rargs.get('start')
  pagination['rows'] = kwargs.get('rows') or rargs.get('rows')

  query_hash = fq['rssid'] + '_' + hashlib.sha1(str(query) + str(rargs) + str(sort) + str(kwargs)).hexdigest()
  if r.exists(query_hash):
    return pickle.loads(r.get(query_hash))

  response = solr.query()
  if isinstance(query, list):
    for q in query:
      if isinstance(q, dict):
        response = response.query(**q)
      else:
        response = response.query(q) # Query is a string
  else:
    response = response.query(query)

  response = response.filter(**fq).paginate(**pagination).sort_by(sort)
  if opeds:
    app.logger.debug(opeds)
    query = solr.Q()
    for oped in opeds:
      query |= solr.Q(OPEDID=str(oped))
    reponse = response.query(query)
    
  response = response.execute()
  app.logger.debug([response.params, response.status])
  if response.status is not 0:
    abort(400)
  cache_response(response, query_hash)
  return list(response)

def cache_response(response, query_hash):
  str_response = pickle.dumps(list(response))
  r.setex(query_hash, 3600, str_response)

@app.route('/v1/find-rssid', methods=['GET'])
@jsonp
def color_api():
  if 'domain' not in request.args:
    abort(400)
  return json.dumps(get_rssid(request.args['domain']))

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

@jsonp
@app.route('/partials/<path:filename>')
def send_static(filename):
  return send_from_directory('../app/partials', filename)

if __name__ == '__main__':
  app.run(debug=True, port=8000)