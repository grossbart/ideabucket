/*

CUSTOM FORM ELEMENTS

Created by Ryan Fait
www.ryanfait.com

Adapted for jQuery by Peter Gassner
www.naehrstoff.ch

LICENSE: Creative Commons Attribution-Share Alike 3.0 Unported
http://creativecommons.org/licenses/by-sa/3.0/

*/

jQuery.fn.styledselect = function(options) {
  
  settings = jQuery.extend({
    onchange: function(){}
  }, options);

  
  // Only accept select elements:
  if (!$(this).is("select")) return false;

  $(this).css({position: "relative", zIndex: 5, opacity: 0});
  
  var span = $('<span />')
     .addClass("select")
     .attr("id", "select_" + $(this).attr("name"))
     .text($("option:selected", this).text());
  $(this).before(span);

  $(this).css({width: span.width()});

  $(this).focus(function() {
    $(this).css({opacity: 100});
  });
  
  $(this).blur(function() {
    $(this).css({opacity: 0})
  });
  
  $(this).change(function() {
    $(this).css({opacity: 0});
    $("#select_"+$(this).attr("name")).text($("option:selected", this).text());
    $(this).css({width: $("#select_"+$(this).attr("name")).width()});
    settings.onchange.call(this);
  });
}