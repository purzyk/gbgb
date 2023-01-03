<script>
import Vue from "vue";

export default {
  props: [
    "track",
    "raceClass",
    "distance",
    "date",
    "time",
    "winner",
    "winnerBadge",
    "winnerLink",
    "raceUrl",
    "meetingUrl",
    "raceUrl",
    "viewDetailsButton",
    "isRepeatedDate"
  ],
  data() {
    return {
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle
    };
  },
  created() {}
};
</script>

<template>
  <div class="LiveResultsRow" :class="{ 'LiveResultsRow--isRepeatedDate' : isRepeatedDate}">
    <div class="LiveResultsRow__track">{{track | longTrackName}}</div>
    <div class="LiveResultsRow__race_class">{{raceClass}}</div>
    <div class="LiveResultsRow__distance">{{distance}}m</div>
    <div class="LiveResultsRow__date">{{date}}</div>
    <div class="LiveResultsRow__time">{{time}}</div>
    <div class="LiveResultsRow__winner">
      <img :src="winnerBadge" alt>
      <a :href="winnerLink">{{ winner }}</a>
    </div>
    <div class="LiveResultsRow__race" v-if="!viewDetailsButton">
      <a :href="raceUrl">
        <span class="Meta">Race</span>
        <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </a>
    </div>
    <div class="LiveResultsRow__meeting" v-if="!viewDetailsButton">
      <a :href="meetingUrl">
        <span class="Meta">Meeting</span>
        <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </a>
    </div>
    <div class="LiveResultsRow__details" v-if="viewDetailsButton">
      <a :href="raceUrl">
        <span class="Meta">View details</span>
        <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </a>
    </div>
  </div>
</template>
