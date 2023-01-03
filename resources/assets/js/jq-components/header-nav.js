import $ from "jquery";

const HIDE = {
  opacity: "0",
  pointerEvents: "none",
};
const SHOW = {
  opacity: "1",
  "pointer-events": "all",
};

class HeaderNav {
  constructor() {
    $(".Header__nav__menu .Item--hasChildren").on("mouseover", this.handleOver);
    $(".Header").on("mouseleave", this.handleLeave);
    $(window).on("scroll", this.handleScroll);
  }
  handleOver() {
    const link = $(this).attr("href");
    $(".Header").addClass("Header--isHover");
    $(".HeaderNavSubmenu__section").css(HIDE);
    $(".HeaderNavSubmenu").css(SHOW);
    const $section = $(`.HeaderNavSubmenu__section[data-section-for-item='${link}']`);
    const $sectionLink = $(`.Header__nav__menu__item .Item[href='${link}']`);
    $(".Header__nav__menu__item .Item").removeClass("Item--sectionIsActive");
    $sectionLink.addClass("Item--sectionIsActive");
    $section.css(SHOW);
  }
  handleLeave() {
    $(".Header").removeClass("Header--isHover");
    $(".HeaderNavSubmenu").css(HIDE);
    $(".HeaderNavSubmenu__section").css(HIDE);
    $(".Header__nav__menu__item .Item").removeClass("Item--sectionIsActive");
  }
  handleScroll() {
    if ($(window).scrollTop() > 0) {
      $(".Header").addClass("Header--isScroll");
    } else {
      $(".Header").removeClass("Header--isScroll");
    }
  }
}

export default HeaderNav;
