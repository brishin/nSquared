$('#colorpickerholder2').ColorPicker({
    flat: true,
    color: '#EFEFEF',
    onSubmit: function(hsb, hex, rgb) {
      $('#colorselector div').css('backgroundColor', '#' + hex);
    }
  });
