from flask import Flask, request, abort, current_app
import redis
import requests
import json
from functools import wraps

import colorsys
from PIL import Image
import scipy.cluster.vq as vq
from StringIO import StringIO
import numpy as np

app = Flask(__name__)
r = redis.StrictRedis(host='localhost', port=6379, db=0)

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

@app.route('/v1/color', methods=['GET'])
@jsonp
def thumb_color_api():
  app.logger.debug(request.args)
  if 't' in request.args:
    thumbs = request.args.getlist('t')
    result = {}
    for i, thumb in enumerate(thumbs):
      thumb_request = requests.get(thumb)
      image = Image.open(StringIO(thumb_request.content))
      result[i] = find_color(image, request.args)
    return json.dumps(result)
  abort(500)


def find_color(image, args):
  MAX_SIZE = 250
  priority = (1, 1.7, 1.8)
  NUM_CLUSTERS = 4
  if 'p1' in args:
    priority = (float(args['p1']), float(args['p2']), float(args['p3']))
  if 'clusters' in args:
    NUM_CLUSTERS = int(args['clusters'])

  if image.size[0] > MAX_SIZE:
    resize_factor = image.size[0] / MAX_SIZE
    image = image.resize((MAX_SIZE / 8, MAX_SIZE / 8)\
        , Image.BICUBIC)

  image_data = list(image.getdata())
  image_data = map(lambda x: rgb_to_hsv(*x), image_data)

  np_array = np.asarray(image_data) * priority
  clusters = vq.kmeans2(np_array, NUM_CLUSTERS, minit='points')[0]
  clusters /= priority
  out_colors = []
  for color in clusters:
    rgb = colorsys.hsv_to_rgb(*color)
    rgb = tuple([255 * x for x in rgb])
    out_colors.append('%02x%02x%02x' % rgb)
  return out_colors

def rgb_to_hsv(r, g, b, *args):
  try:
    return colorsys.rgb_to_hsv(r / 255.0, g / 255.0, b / 255.0)
  except ZeroDivisionError, e:
    return None

if __name__ == '__main__':
  app.run(debug=True)