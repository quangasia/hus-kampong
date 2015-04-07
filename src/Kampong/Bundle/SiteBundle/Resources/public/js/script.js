/**
 * @name Site
 * @description Define global variables and functions
 * @version 1.0
 */
var Site = (function($, window, undefined) {
  var privateVar = 1;

  function privateMethod1() {
    // todo
  }
  return {
    publicVar: 1,
    publicObj: {
      var1: 1,
      var2: 2
    },
    publicMethod1: privateMethod1
  };

})(jQuery, window);

jQuery(function() {
  Site.publicMethod1();
});

/**
 *  @name plugin
 *  @description description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;(function($, window, undefined) {
  var heightFull = '.product-block',
      win = $(window);

  function Plugin(element, options) {
    this.element = $(element);
    this.options = $.extend({}, $.fn[heightFull].defaults, options);
    this.init();
  }

  Plugin.prototype = {
    init: function() {
      var that = this,
          element = that.element,
          heightElment = element.find('.thumbnail').height();

      if($('.product-block .thumbnail').height() > element.find('.thumbnail img').height()){
        element.find('.thumbnail').css({
          height: $('.product-block .thumbnail').height()
        });
      }

      win.on('resize', function (){
        element.find('.thumbnail')[0].style.height = null;
        setTimeout(function(){
          element.find('.thumbnail').css({
            height: $('.product-block .thumbnail').height()
          });
        }, 500);
      }).trigger('resize');
    },
    destroy: function() {
      $.removeData(this.element[0], heightFull);
    }
  };

  $.fn[heightFull] = function(options, params) {
    return this.each(function() {
      var instance = $.data(this, heightFull);
      if (!instance) {
        $.data(this, heightFull, new Plugin(this, options));
      } else if (instance[options]) {
        instance[options](params);
      } else {
        window.console && console.log(options ? options + ' method is not exists in ' + heightFull : heightFull + ' plugin has been initialized');
      }
    });
  };

  $(function() {
    $(heightFull)[heightFull]();
  });

}(jQuery, window));
