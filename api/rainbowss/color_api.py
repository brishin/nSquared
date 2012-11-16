from flask import Flask, request, abort, current_app
import sunburnt
import redis


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
  return 'OK'

@app.route('/update', methods=['GET'])
def update():
  if 'rssid' not in request.args:
    abort(400)
  rssid = request.args['rssid']
  r.rpush('updateQueue', rssid)
  return 'OK'

if __name__ == '__main__':
  app.run(port=9051, host='0.0.0.0')