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

$( document ).ready(function() {
  $(window).load(function() {
    $('.slideshow').flexslider({
      animation: "slide",
      slideshow: true,
      controlNav: false,
      itemWidth: 155,
      itemMargin: 20,
      minItems: 1,
      maxItems: 4
    });
  });
  $('#myTab a').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
  });
});