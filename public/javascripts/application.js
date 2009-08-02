/* Run when document is ready
-----------------------------------------*/
$(document).ready(function() {
  // Styling
  $("select.styled").styledselect();
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
  
  // Form Handling
  $("#questionnaire").submit(function() {
    $.post($(this).attr("action"), $(this).serialize(), function(data, status) {
      var t = "<li>{title}</li>";
      var answer = "";
      if (data.length > 0) {
        answer = $.map(data, function(idea) {
          return $.nano(t, idea);
        }).join("");
      } else {
        answer = "<li>Nichts passendes gefunden.</li>"
      }
      $("#results ul").empty().append(answer);
    }, "json");
    return false;
  });
});

