<script>
import Vue from "vue";
var moment = require("moment");

export default {
  props: ["full", "isLoading", "rows", "viewDetailsButton", "enablesorting"],
  data() {
    return {
      sortBy: (this.full ? "date" : false),
      sortOrder: (this.full ? "desc" : "asc"),
      resultsPageUrl: window.__GBGB_GLOBAL__.pages.meeting,
      greyhoundPageUrl: window.__GBGB_GLOBAL__.pages.greyhound,
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron
    };
  },
  methods: {
    getTrapBadge(trapNumber) {
      return window.__GBGB_GLOBAL__.trapBadges["trapBadge" + trapNumber];
    },
    getDogUrl(dogId) {
      return this.greyhoundPageUrl + "?greyhoundId=" + dogId;
    },
    getRaceUrl(meetingId, raceId) {
      return (
        this.resultsPageUrl + "?meetingId=" + meetingId + "&raceId=" + raceId
      );
    },
    getMeetingUrl(meetingId) {
      return this.resultsPageUrl + "?meetingId=" + meetingId;
    },
    formatDate(date) {
      const d = moment(date, "DD/MM/YYYY");
      if (moment(d).isSame(moment(), "day")) {
        return "Today";
      }
      if (moment(d).isSame(moment().subtract(1, "days"), "day")) {
        return "Yesterday";
      }
      return d.format("DD/MM");
    },
    formatTime(time) {
      return time.substr(0, 5);
    },
    sort(sortBy) {
      if (!this.enablesorting) {
        return;
      }
      if (this.sortBy === sortBy && this.sortOrder === "desc") {
        this.sortBy = false;
      } else if (this.sortBy === sortBy && this.sortOrder === "asc") {
        this.sortOrder = "desc";
      } else {
        this.sortBy = sortBy;
        this.sortOrder = "asc";
      }
      this.$emit("sort", { sortBy: this.sortBy, sortOrder: this.sortOrder });
    },
    isRepeatedDate(index) {
      if (index === 0) {
        return false;
      }
      const date = this.rows[index].raceDate;
      const previousDate = this.rows[index - 1].raceDate;
      return date === previousDate;
    }
  }
};
</script>

<template>
  <div
    class="LiveResultsTable"
    :class="{'LiveResultsTable--isLoading': isLoading,'LiveResultsTable--sorting': enablesorting,'LiveResultsTable--sortAsc': sortOrder === 'asc'}"
  >
    <div class="LiveResultsTableHeader">
      <div
        @click="sort('track')"
        :class="{ 'LiveResultsTableHeader__track--sortBy': sortBy === 'track'}"
        class="LiveResultsTableHeader__track"
      >
        <span class="Meta">Track</span>
        <span v-if="full" v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </div>
      <div
        @click="sort('class')"
        :class="{ 'LiveResultsTableHeader__race_class--sortBy': sortBy === 'class'}"
        class="LiveResultsTableHeader__race_class"
      >
        <span class="Meta">Class</span>
        <span v-if="full" v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </div>
      <div
        @click="sort('distance')"
        :class="{ 'LiveResultsTableHeader__distance--sortBy': sortBy === 'distance'}"
        class="LiveResultsTableHeader__distance"
      >
        <span class="Meta">Distance</span>
        <span v-if="full" v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </div>
      <div
        @click="sort('date')"
        :class="{ 'LiveResultsTableHeader__date--sortBy': sortBy === 'date'}"
        class="LiveResultsTableHeader__date"
      >
        <span class="Meta">Date</span>
        <span v-if="full" v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </div>
      <div
        @click="sort('time')"
        :class="{ 'LiveResultsTableHeader__time--sortBy': sortBy === 'time'}"
        class="LiveResultsTableHeader__time"
      >
        <span class="Meta">Time</span>
        <span v-if="full" v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </div>
      <div class="LiveResultsTableHeader__winner">Winner</div>
    </div>
    <spinner :isActive="isLoading"/>
    <live-results-row
      :key="`row-${index}`"
      v-for="(row,index) in rows"
      :track="row.trackName"
      :raceClass="row.raceClass"
      :distance="row.raceDistance"
      :date="formatDate(row.raceDate)"
      :time="formatTime(row.raceTime)"
      :winner="row.dogName"
      :winnerBadge="getTrapBadge(row.trapNumber)"
      :winnerLink="getDogUrl(row.dogId)"
      :raceUrl="getRaceUrl(row.meetingId,row.raceId)"
      :meetingUrl="getMeetingUrl(row.meetingId)"
      :viewDetailsButton="viewDetailsButton"
      :isRepeatedDate="isRepeatedDate(index)"
    ></live-results-row>
  </div>
</template>
