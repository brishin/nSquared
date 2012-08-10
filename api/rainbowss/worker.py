import redis
import fetcher
import daemon
import argparse

r = redis.StrictRedis(host='localhost', port=6379, db=0)

parser = argparse.ArgumentParser('Color queue worker.')
parser.add_argument('--pidfile')
args = parser.parse_args()

if args.get('pidfile'):
  context = daemon.DaemonContext(
    pidfile=lockfile.FileLock(args.get('pidfile'))
    )

def worker_daemon():
  while True:
    (collection, rssid) = r.blpop('colorQueue', 0)
    fetcher.insert_thumbs(rssid)

with daemon.DaemonContext():
  worker_daemon()