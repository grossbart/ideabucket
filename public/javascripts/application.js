/* Global variables
-----------------------------------------*/
var FILLER = "______";


/* Helper functions
-----------------------------------------*/
function isNumeric(val) {
  var RE = /^-{0,1}\d*\.{0,1}\d+$/;
  return RE.test(val);
}


/* Run when document is ready
-----------------------------------------*/
$(document).ready(function() {
  $("select[rel]").each(function() {
    var id = $(this).attr("rel");
    if ($.cookie(id)) {
      $('option[value='+$.cookie(id)+']', this).attr("selected", true);
    }
  });
  
  $("body.index input[rel]").each(function() {
    var id = $(this).attr("rel");
    if ($.cookie(id)) {
      $(this).attr("value", $.cookie(id));
    }
    $(this).blur(function() {
      $("#number_of_results").html(parseInt(Math.random() * 200));
      $.cookie($(this).attr("rel"), $(this).val(), { expires: 14 });
    });
  });
  
  $(".whisper input").each(function() {
    $(this).focus(function() {
      $(this).closest("p").removeClass("whisper");
    });
    $(this).blur(function() {
      if ($(this).val() == "" || $(this).val() == "0") {
        $(this).closest("p").addClass("whisper");
      }
    })
  });

  
  $('#clear_cookies').click(function() {
    $("[rel]").each(function() {
      $.cookie($(this).attr("rel"), null);
    });
  });
	$('abbr').each(function(){
		var dt = $(this).html();
		var dd = $(this).attr('alt');
		var note = '<dt>' + dt + '</dt><dd>' + dd + '</dd>';
		$('dl.notes').append(note)
	})
});


/* Style select elements
-----------------------------------------*/
jQuery(document).ready(function() {
  $("select.styled").each(function() {
    $(this).styledselect({
      onchange: function() {
        $("#number_of_results").html(parseInt(Math.random() * 200));
        $.cookie($(this).attr("rel"), $(this).val(), { expires: 14 });
      }
    });
  });
});
