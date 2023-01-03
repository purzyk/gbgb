import $ from "jquery";
import "slick-carousel";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

class BoardMembers {
  constructor() {
    this.$carousel = $(".BoardMembers__carousel");
    this.initSlider();
    this.sliderNavigation();
    this.activeSlide();
  }

  setAspectRatio() {
    const calculatedHeight =
      $(".BoardMembers__carousel .slick-slide").width() * 1.5;
    $(".MemberBlock__image").css("height", calculatedHeight);
  }

  initSlider() {
    this.$carousel.on("init", this.setAspectRatio);
    this.$carousel.on("breakpoint", this.setAspectRatio);
    this.$carousel.slick({
      infinite: true,
      slidesToShow: 5,
      touchMove: false,
      arrows: false,
      centerMode: true,
      centerPadding: "20px",
      responsive: [
        {
          breakpoint: 1600,
          settings: {
            slidesToShow: 4,
          },
        },
        {
          breakpoint: 1060,
          settings: {
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
          },
        },
        {
          breakpoint: 300,
          settings: "unslick", // destroys slick
        },
      ],
    });
  }

  sliderNavigation() {
    $(".BoardMembers__header__nav__button--next").on("click", () => {
      this.$carousel.slick("slickGoTo", this.getSlideNumber(1));
    });

    $(".BoardMembers__header__nav__button--prev").on("click", () => {
      this.$carousel.slick("slickGoTo", this.getSlideNumber(-1));
    });
  }

  // slide to scroll to ammended for breakpoint
  // -1 for prev, 1 for next
  getSlideNumber(direction) {
    const slideCount = this.$carousel.slick("getSlick").slideCount;
    const currentSlide = this.$carousel.slick("slickCurrentSlide");
    const documentWidth = $(document).width();
    let slidesToScroll = direction;
    if (documentWidth > 800) {
      slidesToScroll = direction * 2;
    }
    if (documentWidth > 1060) {
      slidesToScroll = direction * 3;
    }
    let targetSlide = currentSlide + slidesToScroll;
    if (targetSlide < 0) {
      targetSlide = slideCount + targetSlide;
    }
    return targetSlide;
  }

  activeSlide() {
    $(document)
      .find(".MemberBlock")
      .on("click", e => {
        e.preventDefault();

        // Define active class
        const ACTIVE_CLASS = "MemberBlock--active";

        const $memberBlock = $(e.target).closest(".MemberBlock");
        const blockId = $memberBlock.data("related");
        const $bioBlock = $("#" + blockId + ".BoardMembers__bio__content");

        // Remove active blocks and hide bio's except this
        $(".MemberBlock")
          .not($memberBlock)
          .removeClass(ACTIVE_CLASS);
        $(".BoardMembers__bio__content")
          .not($bioBlock)
          .hide();

        // Set active class for specific block
        if ($memberBlock.hasClass(ACTIVE_CLASS)) {
          $memberBlock.removeClass(ACTIVE_CLASS);
          $bioBlock.hide();
        } else {
          $memberBlock.addClass(ACTIVE_CLASS);
          $bioBlock.show();
          $("html, body").animate(
            {
              scrollTop: $bioBlock.offset().top - 200,
            },
            500
          );
        }
      });
  }
}

export default BoardMembers;
