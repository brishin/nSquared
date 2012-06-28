from PIL import Image
import numpy as np
import colorsys

class RImage:
  def __init__(self, path):
    image = Image.open(path)
    self.image_array = np.asarray(image)
    for cell in for x in self.image_array:

    for cell in (cell for row in a for cell in row):
      cell = colorsys.rgb_to_hsv(*cell)


im = Image.open('IMG_20110603_162102.jpg')
a = np.asarray(im)
b = a.reshape((-1, 3))