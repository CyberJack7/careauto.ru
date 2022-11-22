$(document).ready(function () {
  $(document).on("submit", "form", function (event) {
    event.preventDefault();
    $.ajax({
      type: $(this).attr("method"),
      url: $(this).attr("action"),
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function () {
        $("#appl-accordion").load(" #appl-accordion");
      },
    });
  });
});
