import Image from PIL
import numpy, colorsys

class RImage:
  def __init__(self, path):
    image = Image.open(path)
    self.image_array = numpy.asarray(image)
    colorsys.rgb_to_hsv(*s[0,0])