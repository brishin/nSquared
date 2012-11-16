from mongoengine import *

connect('nSquared') # default localhost settings.

class Site(Document):
  rssid = IntField(required=True)
  domain = StringField()
  last_updated = DateTimeField()
  meta = {
    'indexes': ['rssid'],
    'collection': 'sites',
    'allow_inheritance': False
  }

class Thumb(Document):
  opedid = StringField(required=True)
  rssid = IntField(required=True)
  url = URLField(required=True)
  l = ListField()
  a = ListField()
  b = ListField()
  prominence = ListField()
  meta = {
    'indexes': ['opedid', 'l', 'a', 'b', 'rssid'],
    'collection': 'thumbs',
    'allow_inheritance': False
  }