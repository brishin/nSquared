import redis
import fetcher
import daemon

r = redis.StrictRedis(host='localhost', port=6379, db=0)

def worker_daemon():
  while True:
    (collection, rssid) = r.blpop('colorQueue', 0)
    fetcher.insert_thumbs(rssid)

if __name__ == '__main__':
  with daemon.DaemonContext():
    worker_daemon()