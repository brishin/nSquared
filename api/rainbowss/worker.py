#!/usr/bin/env python

import sys, time
import redis
import fetcher
from daemon import Daemon

r = redis.StrictRedis(host='localhost', port=6379, db=0)

class MyDaemon(Daemon):
  def run(self):
    while True:
      (collection, rssid) = r.blpop('colorQueue', 0)
      fetcher.insert_thumbs(rssid)

if __name__ == "__main__":
  if len(sys.argv) == 3:
    pid_file = sys.argv[2]
  else:
    pid_file = 'worker.pid'
  daemon = MyDaemon(pid_file)
  if len(sys.argv) >= 2:
    if 'start' == sys.argv[1]:
      daemon.start()
    elif 'stop' == sys.argv[1]:
      daemon.stop()
    elif 'restart' == sys.argv[1]:
      daemon.restart()
    else:
      print "Unknown command"
      sys.exit(2)
    sys.exit(0)
  else:
    print "usage: %s start|stop|restart [pidfile]" % sys.argv[0]
    sys.exit(2)