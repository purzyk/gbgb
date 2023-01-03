import $ from "jquery";
import "slick-carousel";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

class Testimonials {
  constructor() {
    this.$carousel = $(".Testimonials__carousel");
    this.initSlider();
    this.sliderNavigation();
    this.seeMore();
  }

  initSlider() {
    this.$carousel.slick({
      infinite: true,
      slidesToShow: 3,
      arrows: false,
      centerMode: true,
      centerPadding: "20px",
      responsive: [
        {
          breakpoint: 1400,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 992,
          settings: "unslick", // destroys slick
        },
      ],
    });
  }

  sliderNavigation() {
    $(".Testimonials__header__nav__button--next").on("click", () => {
      this.$carousel.slick("slickNext");
    });

    $(".Testimonials__header__nav__button--prev").on("click", () => {
      this.$carousel.slick("slickPrev");
    });
  }

  seeMore() {
    $(".Testimonials__seeMore").on("click", function() {
      $(".Testimonials__carousel .TestimonialBlock").show();
      $(".Testimonials__seeMore").hide();
    });
  }
}

export default Testimonials;
