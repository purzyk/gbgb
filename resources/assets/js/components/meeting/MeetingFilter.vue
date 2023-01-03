<script>
import Vue from "vue";
import $ from "jquery";

export default {
  props: ["races", "defaultRaceId"],
  mounted() {
    this.checkOverflow();
    $(window).on("resize", this.checkOverflow.bind(this));
    this.scrollToSelected();
  },
  data() {
    return {
      currentRaceId: this.defaultRaceId
        ? parseInt(this.defaultRaceId, 10)
        : false,
      scrollButtons: false,
      maxRight: 0,
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle
    };
  },
  methods: {
    formatTime(race) {
      if (race.raceTime !== "") {
        return race.raceTime.substr(0, 5);
      } else {
        return race.raceNumber;
      }
    },
    setRace(raceId) {
      this.currentRaceId = raceId;
      this.$emit("change", this.currentRaceId);
    },
    checkOverflow() {
      let totalWidth = 0;
      let maxWidth = $(".MeetingFilter__hours").innerWidth();
      $(".MeetingFilter__button").each(function() {
        totalWidth += $(this).outerWidth(true);
      });
      this.maxRight = totalWidth - maxWidth + 48;
      this.scrollButtons = totalWidth > maxWidth;
    },
    scrollToSelected() {
      const activeLeft =
        $(".MeetingFilter__button--active").offset().left + 100;
      const barWidth = $(".MeetingFilter__hours").width();
      setTimeout(() => {
        if (activeLeft > barWidth) {
          let offset = parseInt(activeLeft - barWidth + 100, 10);
          offset = offset < this.maxRight ? offset : this.maxRight;
          $(this.$refs.hours).scrollLeft(offset);
        }
      }, 250);
    },
    scroll(amount) {
      const newVal = $(this.$refs.hours).scrollLeft() + amount;
      $(this.$refs.hours).scrollLeft(newVal);
    }
  }
};
</script>

<template>
  <div class="MeetingFilter" :class="{'MeetingFilter--scroll' : scrollButtons}">
    <div
      class="MeetingFilter__scrollButton MeetingFilter__scrollButton--left"
      v-if="scrollButtons"
      @click="scroll(-48)"
    >
      <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
    <div class="MeetingFilter__hours" ref="hours">
      <div
        class="MeetingFilter__button"
        :class="{'MeetingFilter__button--active' : currentRaceId === false}"
        @click="setRace(false)"
      >Full meeting</div>
      <div
        class="MeetingFilter__button"
        :key="`race-${index}`"
        :class="{'MeetingFilter__button--active' : currentRaceId === race.raceId}"
        v-for="(race,index) in races"
        @click="setRace(race.raceId)"
      >{{formatTime(race)}}</div>
      <div class="MeetingFilter__filler" v-if="scrollButtons"></div>
    </div>
    <div
      class="MeetingFilter__scrollButton MeetingFilter__scrollButton--right"
      v-if="scrollButtons"
      @click="scroll(48)"
    >
      <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
  </div>
</template>
