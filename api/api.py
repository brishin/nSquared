from flask import Flask, request, abort, current_app
from functools import wraps
import json, requests

app = Flask(__name__)
app.config['solr_url'] = 'http://localhost:2000/solr/select'
# Whitelist of parameters allowed to send to solr
app.config['allowed_params'] = ['rows', 'start']

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
      return json.dumps(data['response']['docs'])

  if solr_request is not None:
    abort(solr_request.status_code)
  else:
    abort(404)

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