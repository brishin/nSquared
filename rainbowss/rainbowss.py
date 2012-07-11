from PIL import Image
import numpy as np
import colorsys
from colormath.color_objects import RGBColor, LabColor
import scipy.cluster.vq as vq
import sys

class RImage:
  def __init__(self, path):
    image = Image.open(path)
    image = image.resize((image.size[0] / 8, image.size[1] / 8)\
        , Image.BICUBIC)
    self.image_array = np.asarray(image).resize((-1,3))
    self.color_array = []

    for color in self.image_array:
      self.color_array.append(RGBColor(*color))


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