from PIL import Image
import numpy as np
import colorsys
from colormath.color_objects import RGBColor, LabColor

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
  def color_c(r, g, b):
    try:
      return colorsys.rgb_to_hsv(r / 255.0, g / 255.0, b / 255.0)
    except ZeroDivisionError, e:
      return None

  image = Image.open('test.jpg')
  image = image.resize((image.size[0] / 8, image.size[1] / 8)\
      , Image.BICUBIC)
  image_array = list(image.getdata())
  image_array = map(lambda x: color_c(*x), image_array)