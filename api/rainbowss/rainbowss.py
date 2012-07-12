from PIL import Image
import numpy as np
import colorsys
import scipy.cluster.vq as vq
import sys

def find_color(image, rargs):
  MAX_SIZE = 250
  priority = (1, 1.7, 1.8)
  NUM_CLUSTERS = 4
  if 'p1' in rargs:
    priority = (float(rargs['p1']), float(rargs['p2']), float(rargs['p3']))
  if 'clusters' in rargs:
    NUM_CLUSTERS = int(rargs['clusters'])

  if image.size[0] > MAX_SIZE:
    resize_factor = image.size[0] / MAX_SIZE
    image = image.resize((MAX_SIZE / 8, MAX_SIZE / 8)\
        , Image.BICUBIC)

  image_data = list(image.getdata())
  image_data = map(lambda x: rgb_to_hsv(*x), image_data)

  np_array = np.asarray(image_data) * priority
  clusters = vq.kmeans2(np_array, NUM_CLUSTERS, minit='points')[0]
  clusters /= priority
  out_colors = []
  for color in clusters:
    rgb = colorsys.hsv_to_rgb(*color)
    rgb = tuple([255 * x for x in rgb])
    out_colors.append('%02x%02x%02x' % rgb)
  return out_colors

def rgb_to_hsv(r, g, b, *args):
  try:
    return colorsys.rgb_to_hsv(r / 255.0, g / 255.0, b / 255.0)
  except ZeroDivisionError, e:
    return None

if __name__ == '__main__':
  def color_c(r, g, b, *args):
    try:
      return colorsys.rgb_to_hsv(r / 255.0, g / 255.0, b / 255.0)
    except ZeroDivisionError, e:
      return None

  priority = (1, 1.7, 1.8)

  if (len(sys.argv) >= 1):
    file_name = sys.argv[1]
  else:
    file_name = 'test.jpg'

  image = Image.open(file_name)
  image = image.resize((image.size[0] / 8, image.size[1] / 8)\
      , Image.BICUBIC)
  image_array = list(image.getdata())
  image_array = map(lambda x: color_c(*x), image_array)
  np_array = np.asarray(image_array) * priority
  clusters = vq.kmeans2(np_array, 4, minit='points')[0]
  clusters /= priority
  out_colors = []
  for color in clusters:
    rgb = colorsys.hsv_to_rgb(*color)
    out_colors.append(tuple([255 * x for x in rgb]))

  for color in out_colors:
    print '%02x%02x%02x' % color 