// Generated by CoffeeScript 1.3.3

angular.module("myApp.directives", []).directive("modalInject", function() {
  var _this = this;
  this.createdElements = [];
  this.cleanup = function() {
    console.log(this.createdElements);
    if (this.createdElements.length > 3) {
      if (this.createdElements[createdElements.length - 1] === this.createdElements[0]) {
        return this.createdElements.shift();
      } else {
        return $(this.createdElements.shift()).empty();
      }
    }
  };
  return function(scope, elm, attrs) {
    var $elm, $elmInject;
    $elm = $(elm);
    $elmInject = $($elm.children('.inject-target'));
    $elm.on('show', function(e) {
      var newElement;
      console.log($elmInject);
      newElement = $('<iframe/>', {
        "class": 'injected-frame',
        src: scope.square.link
      });
      $elmInject.append(newElement);
      _this.createdElements.push($elmInject);
      _this.cleanup();
      return document.body.style.overflow = "hidden";
    });
    return $elm.on('hide', function(event) {
      return document.body.style.overflow = "visible";
    });
  };
});