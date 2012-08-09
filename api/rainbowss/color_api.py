from flask import Flask, request, abort, current_app
import fetcher
import sunburnt
import redis

app = Flask(__name__)
r = redis.StrictRedis(host='localhost', port=6379, db=0)

@app.route('/index', methods=['GET'])
def index(rssid=None):
  if 'rssid' not in request.args and rssid is None:
    abort(400)
  rssid = rssid or request.args['rssid']
  r.rpush('colorQueue', rssid)
  # fetcher.insert_thumbs(rssid)
  return 'OK'

if __name__ == '__main__':
  app.run(port=9051, host='0.0.0.0')