import redis
import fetcher

r = redis.StrictRedis(host='localhost', port=6379, db=0)

def worker_daemon():
  while True:
    rssid = r.blpop('colorQueue', 0)
    try:
      fetcher.insert_thumbs(rssid)
    except Exception, e:
      print e

if __name__ == '__main__':
  worker_daemon()