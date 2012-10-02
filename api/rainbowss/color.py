from flask import Flask, request, abort, current_app
import redis
import requests
import json
from functools import wraps

from PIL import Image
from StringIO import StringIO
import colorific

app = Flask(__name__)
r = redis.StrictRedis(host='localhost', port=6379, db=0)

'''
External, application specific api for color.
'''

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

def find_color(image, rargs):
  palette = colorific.extract_colors(image)
  out_colors = [rgb_to_hex(c.value) for c in palette.colors]
  return out_colors

def rgb_to_hex(color):
    return '%.02x%.02x%.02x' % color

if __name__ == '__main__':
  app.run(debug=True)