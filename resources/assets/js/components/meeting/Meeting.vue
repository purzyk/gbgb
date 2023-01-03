<script>
import Vue from "vue";
import ResultsApi from "../../services/ResultsApi";
var moment = require("moment");

export default {
  props: ["meetingid", "raceid", "nonce"],
  created() {
    this.getMeeting();
  },
  data() {
    return {
      currentRaceId: this.raceid !== "" ? parseInt(this.raceid, 10) : false,
      meeting: null,
      races: [],
      isLoading: true,
      resultsPageUrl: window.__GBGB_GLOBAL__.pages.results,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow
    };
  },
  methods: {
    getMeeting(meetingid) {
      ResultsApi.getMeeting(this.meetingid).then(response => {
        this.meeting = response[0];
        this.races = response[0].races;
        this.isLoading = false;
      });
    },
    handleRaceChange(e) {
      this.currentRaceId = e;
    }
  },
  computed: {
    displayedRaces() {
      return this.races.filter(race => {
        return (
          this.currentRaceId === false || this.currentRaceId === race.raceId
        );
      });
    }
  },
  filters: {
    formatDate(date) {
      return moment(date, "DD/MM/YYYY").format("dddd Do MMMM YYYY");
    }
  }
};
</script>

<template>
  <section class="Meeting">
    <div class="Meeting__header" v-if="meeting">
      <time class="Meeting__header__date">{{ meeting.meetingDate | formatDate }}</time>
      <div class="Meeting__header__title">
        <a
          :href="resultsPageUrl"
          aria-label="Back to previous page"
          class="Meeting__header__title__back"
        >
          <span v-html="arrowIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </a>
        <h1 class="Meeting__header__title__meta">{{ meeting.trackName | longTrackName }}</h1>
      </div>
    </div>
    <div class="Meeting__inner" v-if="meeting">
      <meeting-filter @change="handleRaceChange" :races="races" :defaultRaceId="raceid"/>
      <meeting-race
        :nonce="nonce"
        :key="`race-${index}`"
        v-for="(race,index) in displayedRaces"
        :race="race"
        :track="meeting.trackName"
      />
    </div>
    <spinner :isActive="isLoading"/>
  </section>
</template>
