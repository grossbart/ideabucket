/*

CUSTOM FORM ELEMENTS

Originally created by Ryan Fait
http://ryanfait.com

Adapted for jQuery by Peter Gassner
www.naehrstoff.ch

Please note: the original script by Ryan Fait is able to also style
radio buttons and check boxes. If you need this functionality, visit
his site for more information:

http://ryanfait.com/resources/custom-checkboxes-and-radio-buttons/

LICENSE: Creative Commons Attribution-Share Alike 3.0 Unported
http://creativecommons.org/licenses/by-sa/3.0/

USAGE:

$(document).ready(function() {
  $(".styled").styledselect(function() {
    // callback code that should be run after options have changed.
  });
});

*/

(function($) {
  $.fn.styledselect = function(options) {
    return this.each(function() {
      // Only accept select elements:
      if (!$(this).is("select")) return false;

      // Options
      options = jQuery.extend({
        onchange: function(){}
      }, (typeof options == "function") ? {onchange: options} : options);

      var extra = $('<span />')
        .addClass("select")
        .attr("id", "select_" + $(this).attr("name"))
        .text($("option:selected", $(this)).text());
         
      $(this)
        .after(extra)
        .css({position: "absolute", zIndex: 5, width: extra.width(), opacity: 0})
        .focus(show)
        .blur(hide)
        .change(change);
      
      function show() {
        $(this).css({opacity: 100});
      }

      function hide() {
        $(this).css({opacity: 0, width: extra.width()});
      }
      
      function change() {
        extra.text($("option:selected", this).text());
        hide.call(this);
        options.onchange.call(this);
      }
    });
  }
})(jQuery);