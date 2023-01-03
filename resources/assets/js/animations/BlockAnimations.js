import Utils from "../utils/index";

class BlockAnimations {
  constructor() {
    //Add Fade in on entry for the following elements
    [
      ".Highlight",
      ".NearestTrack__inner",
      ".PageGridItem",
      ".OneColumnText",
      ".TwoColumnsText",
      ".TwoColumnsText__row",
      ".TextWithBg__inner",
      ".TextRedSide__inner",
      ".SimpleImage__inner",
      ".SingleImage__inner",
      ".DoubleImage__inner",
      ".MiniNews__grid__row",
      ".Careers__inner",
      ".videoWrapper",
      ".videoCarouselWrapper",
      ".Careers__inner",
      ".Publications",
      ".BoardMembers",
      ".ContactForm",
      ".Testimonials",
      ".RaceCoursesBlock",
    ].forEach(element => {
      Utils.inView(element, "TransitionFadeIn");
    });
    Utils.inView(".Hero__inner", "TransitionFadeIn", function() {}, "-10%");
  }
}
export default BlockAnimations;
