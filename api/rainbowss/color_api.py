from flask import Flask, request, abort, current_app
import fetcher
import sunburnt

app = Flask(__name__)
SOLR_URL = 'http://10.10.10.31:8443/solr/'
solr = sunburnt.SolrInterface(SOLR_URL)

@app.route('/', methods=['GET'])
def index(rssid=None):
  if 'rssid' not in request.args and rssid is None:
    abort(400)
  rssid = rssid or request.args['rssid']
  fetcher.insert_thumbs(rssid)

if __name__ == '__main__':
  app.run(port=9000)