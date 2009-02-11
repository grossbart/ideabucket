function updateResults(name, value) {
  $.post("/find", {"id": name, "value": value}, function(data, status) {
    if (status == 'success') {
      html = data;
    } else {
      html = "<p>Nichts gefunden...</p>";
    }
    $("#results").html(html);
  });
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
  
  $("select.styled").each(function() {
      $(this).styledselect({
      onchange: function() {
        $.cookie($(this).attr("rel"), $(this).val(), { expires: 14 });
        updateResults($(this).attr("name"), $(this).val());
      }
    });
  });
  
  $("body.index input[rel]").each(function() {
    var id = $(this).attr("rel");
    if ($.cookie(id)) {
      $(this).attr("value", $.cookie(id));
    }
    $(this).blur(function() {
      $.cookie($(this).attr("rel"), $(this).val(), { expires: 14 });
      updateResults($(this).attr("name"), $(this).val());
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

  // Cookie handling (wird ausgelagert zu Sinatra)
  $('#clear_cookies').click(function() {
    $("[rel]").each(function() {
      $.cookie($(this).attr("rel"), null);
    });
  });
  
  // Footnotes
  $('abbr').each(function(){
    var dt = $(this).html();
    var dd = $(this).attr('alt');
    var note = '<dt>' + dt + '</dt><dd>' + dd + '</dd>';
    $('dl.notes').append(note)
  });
});

