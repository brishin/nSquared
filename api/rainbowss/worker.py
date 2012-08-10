import redis
import fetcher
import daemon
import argparse
import lockfile

r = redis.StrictRedis(host='localhost', port=6379, db=0)

parser = argparse.ArgumentParser('Color queue worker.')
parser.add_argument('--pidfile')
args = vars(parser.parse_args())

if args.get('pidfile'):
  context = daemon.DaemonContext(
    pidfile=lockfile.FileLock(args.get('pidfile'))
    )
else:
  context = daemon.DaemonContext()

def worker_daemon():
  while True:
    (collection, rssid) = r.blpop('colorQueue', 0)
    fetcher.insert_thumbs(rssid)

with context:
  worker_daemon()