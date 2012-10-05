from flask import Flask, request, abort, current_app
import fetcher
import sunburnt
import redis
import pickle

app = Flask(__name__)
r = redis.StrictRedis(host='localhost', port=6379, db=0)

'''
Internal API for updating and adding thumbnails to the database.
'''

@app.route('/index', methods=['GET'])
def index(rssid=None):
  if 'rssid' not in request.args and rssid is None:
    abort(400)
  rssid = rssid or request.args['rssid']
  r.rpush('colorQueue', rssid)
  # fetcher.insert_thumbs(rssid)
  return 'OK'

@app.route('/update', methods=['GET'])
def update():
  if 'rssid' not in request.args and 'last_updated' not in request.args:
    abort(400)
  msg = {}
  msg['rssid'] = request.args['rssid']
  # Seconds from UNIX epoch, ex. time.time()
  msg['last_updated'] = request.args['last_updated']
  r.rpush('updateQueue', pickle.dumps(msg))
  return 'OK'

if __name__ == '__main__':
  app.run(port=9051, host='0.0.0.0')