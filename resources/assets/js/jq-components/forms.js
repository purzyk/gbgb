import $ from "jquery";
class Forms {
  constructor() {
    $("input, textarea").focus(function() {
      $(this)
        .closest(".floating-label")
        .addClass("focus");
    });
    $("input, textarea").blur(function() {
      $(this)
        .closest(".floating-label")
        .removeClass("focus");
      if ($(this).val().length > 0) {
        $(this)
          .closest(".floating-label")
          .addClass("focus");
      }
    });
    $("input, textarea").on("change", function() {
      if ($(this).val().length == 0) {
        $(this)
          .closest(".floating-label")
          .removeClass("focus");
      }
    });
    $(".wpcf7-radio .wpcf7-list-item").wrap(
      "<label class='input_label'></label>"
    );
    $(".wpcf7-checkbox .wpcf7-list-item").wrap(
      "<label class='input_label'></label>"
    );
  }
}

export default Forms;
