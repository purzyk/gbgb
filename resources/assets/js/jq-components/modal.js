import $ from "jquery";

class Modal {
  constructor() {
    $("a[href='#search']").click(function() {
      $(".SearchModal").addClass("--active");
      setTimeout(() => {
        window.vm.$refs.searchModalInput.handleCLick();
      }, 200);
    });
    $(".SearchModal__close").click(function() {
      $(this)
        .parent()
        .removeClass("--active");
    });
  }
}

export default Modal;
