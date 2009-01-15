
/*

CUSTOM FORM ELEMENTS

Created by Ryan Fait
www.ryanfait.com

Adapted for jQuery by Peter Gassner
www.naehrstoff.ch

The only thing you need to change in this file is the following
variables: checkboxHeight, radioHeight and selectWidth.

Replace the first two numbers with the height of the checkbox and
radio button. The actual height of both the checkbox and radio
images should be 4 times the height of these two variables. The
selectWidth value should be the width of your select list image.

You may need to adjust your images a bit if there is a slight
vertical movement during the different stages of the button
activation.

Visit http://ryanfait.com/ for more information.

LICENSE: Creative Commons Attribution-Share Alike 3.0 Unported
http://creativecommons.org/licenses/by-sa/3.0/

*/

$(document).ready(function() {
  $("select.styled").each(function() {
    $(this).css({position: "relative", zIndex: 5, opacity: 0});
    
    var span = $('<span />')
       .addClass("select")
       .attr("id", "select" + this.name)
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
      $("#select"+this.name).text($("option:selected", this).text());
      $(this).css({width: $("#select"+this.name).width()});
    });
  });
});