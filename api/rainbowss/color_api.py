from flask import Flask, request, abort, current_app
import fetcher
import sunburnt

app = Flask(__name__)
SOLR_URL = 'http://10.10.10.31:8443/solr/'
solr = sunburnt.SolrInterface(SOLR_URL)

@app.route('/', methods=['GET'])
def index():
if 'rssid' not in request.args:
  abort(400)