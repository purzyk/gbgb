import $ from "jquery";
import "slick-carousel";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

class VideoCarousel {
  constructor() {
    this.$carousel = $(".videoCarouselWrapper__container__carousel");
    this.$main = $(".videoCarouselWrapper__container__main iframe");
    this.initSlider();
    this.sliderNavigation();
    this.changeVideoSrc();
  }

  setAspectRatio() {
    // maybe needed
  }

  initSlider() {
    this.$carousel.on("init", this.setAspectRatio);
    this.$carousel.on("breakpoint", this.setAspectRatio);
    this.$carousel.on("breakpoint", () => {
      setTimeout(() => {
        this.changeVideoSrc();
      }, 0);
    });
    this.$carousel.slick({
      infinite: true,
      slidesToShow: 2,
      touchMove: false,
      arrows: false,
      responsive: [
        {
          breakpoint: 1600,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 1060,
          settings: {
            slidesToShow: 2,
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
    $(".videoCarouselWrapper__container__carousel__nav__button--next").on("click", () => {
      this.$carousel.slick("slickNext");
    });

    $(".videoCarouselWrapper__container__carousel__nav__button--prev").on("click", () => {
      this.$carousel.slick("slickPrev");
    });
  }

  changeVideoSrc() {
    $(".videoCarouselWrapper__container__carousel__item").on("click", e => {
      e.preventDefault();

      // Get iframe src
      let src = $(e.target)
        .find("iframe")
        .attr("src");
      this.$main.attr("src", src);
    });
  }
}

export default VideoCarousel;
