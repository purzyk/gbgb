import $ from "jquery";

class Hero {
  constructor() {
    if (!$(".Hero").length) return;
    this.positionOverlappingImage();
    this.setHeroHeight();
    $(".Hero__image img").on("load", this.setHeroHeight);
    $(window).on("resize", this.positionOverlappingImage);
    $(window).on("resize", this.setHeroHeight);
  }

  positionOverlappingImage() {
    if (!$(".HeroOverlapping").length) return;
    const $heroImage = $(".Hero__image img");
    const $overlapImage = $(".HeroOverlapping");
    const offsetAsFraction =
      $overlapImage.attr("data-hero-offset") /
      $heroImage.attr("data-hero-naturalHeight");
    const top = offsetAsFraction * $heroImage[0].height;
    $overlapImage.css("top", top);
    if ($(".OverlapsHero").offset().top <= top) {
      $overlapImage.css("display", "none");
    } else {
      $overlapImage.css("display", "block");
    }
  }

  setHeroHeight() {
    const $heroImage = $(".Hero__image img");
    $(".Hero__image").css("height", $heroImage[0].height);
  }
}

export default Hero;
