function updateResults(name, value) {
  $.post("/find", {"id": name, "value": value}, function(data, status) {
    if (status == 'success') {
      html = data;
    } else {
      html = "<p>Nichts gefunden...</p>";
    }
    $("#results ul").prepend("<li>"+html+"</li>");
  });
}


/* Run when document is ready
-----------------------------------------*/
$(document).ready(function() {
  
  $("select.styled").styledselect(function() {
    updateResults($(this).attr("name"), $(this).val());
  });

  // $("select.styled").each(function() {
  //   $(this).styledselect(function() {
  //     updateResults($(this).attr("name"), $(this).val());
  //   });
  // });
  
  $("body.index input").each(function() {
    $(this).blur(function() {
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
});

