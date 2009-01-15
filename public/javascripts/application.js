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
  $.cookie($(this).attr("rel"), value, { expires: 14 });
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
  
  $('#clear_cookies').click(function() {
    $("[rel]").each(function() {
      $.cookie($(this).attr("rel"), null);
    });
  });
});


/* hook up the forms
-----------------------------------------*/
$(document).ready(function() {
  // Form for numbers
  $('[rel=number_of_persons], [rel=amount_of_money]').editable(formSave, { 
    tooltip : "Move mouseover to edit...",
    event   : "click",
    //onblur  : "submit",
    style   : "inherit",
    type    : "number"
  });

  // Form for amount of money
  $("[rel=date]").editable(formSave, {
    event  : "click",
    delay  : 1500,
    data   : dateList,
    type   : "select",
    onblur : "submit",
    style  : "inherit"
  }).change(function() {
    $('form', this).submit();
  });
  
  // Form for the available time
  $("[rel=time]").editable(formSave, {
    event  : "click",
    delay  : 1500,
    data   : timeList,
    type   : "select",
    onblur : "submit",
    style  : "inherit"
  }).change(function() {
    $('form', this).submit();
  });
});


/* jeditable input types
-----------------------------------------*/
$.editable.addInputType("number", {
  element : function(settings, original) {
    var input = $('<input type="text">');
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





/* Style select elements
-----------------------------------------*/
jQuery(document).ready(function() {
  $("select.styled").each(function() {
    $(this).styledselect();
  });
});
