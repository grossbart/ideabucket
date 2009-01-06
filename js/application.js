/* Global variables
-----------------------------------------*/
var FILLER = "______";


/* Helper functions
-----------------------------------------*/
function isNumeric(val) {
  var RE = /^-{0,1}\d*\.{0,1}\d+$/;
  return RE.test(val);
}

function formSave(value, settings) { 
  $("#number_of_results").html(parseInt(Math.random() * 200));
  $.cookie("number_of_persons", value, { expires: 14 });
  return(value);
}


/* Run when document is ready
-----------------------------------------*/
$(document).ready(function() {
  // Prepare edit fields
  $('.edit').each(function() {
    var name = $(this).attr("rel");
    if ($.cookie(name)) {
      $(this).html($.cookie(name));
    } else {
      $(this).html(FILLER);
    }
  });
  
  // Form for numbers
  $('.form_number').editable(formSave, { 
    tooltip   : "Move mouseover to edit...",
    event     : "mouseover",
    onblur    : "submit",
    style     : "inherit",
    type      : "number"
  });
});


/* jeditable input types
-----------------------------------------*/
$.editable.addInputType("number", {
  element : function(settings, original) {
    var input = $('<input type="text" size="3">');
    $(this).append(input);
    return(input);
  },
  submit: function (settings, original) {
    var value = $("input", this).val();
    if (isNumeric(value)) {
      $("input", this).val(value);
    } else {
      $("input", this).val(FILLER);
    }
  },
  content : function(string, settings, original) {
    var value = (isNumeric(string)) ? string : "";
    $("input", this).val(value);
  }
});