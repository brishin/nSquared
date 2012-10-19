#!/usr/bin/env python
import redis
import fetcher

r = redis.StrictRedis(host='localhost', port=6379, db=0)

'''
Worker daemon to execute the commands in the queue.
'''

while True:
  (collection, rssid) = r.blpop(['colorQueue', 'updateQueue'], 0)
  try:
    if collection == 'colorQueue':
      fetcher.insert_thumbs(rssid)
    elif collection == 'updateQueue':
      fetcher.update_thumbs(rssid)
  except Exception, e:
    raise e
  else:
    r.rpush(collection, rssid)