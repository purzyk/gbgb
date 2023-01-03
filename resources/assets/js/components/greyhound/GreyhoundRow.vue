<script>
import Vue from "vue";
import Utils from "../../utils/index";
var moment = require("moment");

export default {
  created() {},
  props: ["row"],
  data() {
    return {
      resultsPageUrl: window.__GBGB_GLOBAL__.pages.meeting,
      greyhoundPageUrl: window.__GBGB_GLOBAL__.pages.greyhound,
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle
    };
  },
  methods: {
    getRaceUrl(meetingId, raceId) {
      return (
        this.resultsPageUrl + "?meetingId=" + meetingId + "&raceId=" + raceId
      );
    },
    formatDate(date) {
      const d = moment(date, "DD/MM/YYYY");
      if (moment(d).isSame(moment(), "day")) {
        return "Today";
      }
      if (moment(d).isSame(moment().subtract(1, "days"), "day")) {
        return "Yesterday";
      }
      return d.format("DD/MM/YY");
    },
    getTrapBadge(trapNumber) {
      return window.__GBGB_GLOBAL__.trapBadges["trapBadge" + trapNumber];
    },
    getDogUrl(dogId) {
      if (!dogId) {
        return "#";
      }
      return this.greyhoundPageUrl + "?greyhoundId=" + dogId;
    },
    beatenDistance() {
      if (this.row.raceWinTime || this.row.resultRunTime) {
        let beatenDistance = Utils.roundDecimals(
          (this.row.resultRunTime - this.row.raceWinTime) / 0.08,
          2
        );
        //round to 1/4
        beatenDistance = Math.round(beatenDistance / 0.25) * 0.25;
        //separate integer part
        let integer = Math.floor(beatenDistance);
        //separate decimals as fraction
        let decimal = Utils.decimalToFraction(beatenDistance - integer);
        //make sure we don't diplay 0 as integer
        if (integer === 0) {
          integer = "";
        }
        //make sure we have necessary space after integer
        if (integer && decimal) {
          decimal = " " + decimal;
        }
        return integer + decimal;
      } else {
        return "";
      }
    }
  },
  filters: {
    breakLine(text) {
      if (text && text.length > 10) {
        let regex = new RegExp(",", "g");
        return text.replace(regex, ", ");
      }
      return text;
    }
  },
  computed: {
    raceType() {
      return this.row.raceType === "Hurdles" ? "H" : "";
    }
  }
};
</script>

<template>
  <div class="GreyhoundRow">
    <div class="GreyhoundRow__date">{{formatDate(row.raceDate)}}</div>
    <div class="GreyhoundRow__track">
      {{ row.trackName | longTrackName }}
      <br />
      {{ row.raceClass }} | {{ row.raceDistance }}{{raceType}} | {{row.raceGoing | dashEmpty }}
    </div>
    <div class="GreyhoundRow__trap">
      <img :src="getTrapBadge(row.trapNumber)" />
    </div>
    <div class="GreyhoundRow__line"></div>
    <div class="GreyhoundRow__stmhcp">{{ row.resultSectionalTime | dashEmpty }}</div>
    <div class="GreyhoundRow__pos">{{ row.resultPosition | dashEmpty }}</div>
    <div class="GreyhoundRow__by">{{ beatenDistance() | dashEmpty }}</div>
    <div class="GreyhoundRow__first">
      <a :href="getDogUrl(row.winnerOr2ndId)">{{ row.winnerOr2ndName | dashEmpty }}</a>
    </div>
    <div class="GreyhoundRow__remarks">{{ row.resultComment | breakLine | dashEmpty }}</div>
    <div class="GreyhoundRow__line"></div>
    <div class="GreyhoundRow__winTime">{{ row.raceWinTime | dashEmpty }}</div>
    <div class="GreyhoundRow__weight">{{ row.resultDogWeight | dashEmpty }}</div>
    <div class="GreyhoundRow__sp">{{ row.SP | dashEmpty }}</div>
    <div class="GreyhoundRow__calcTm">{{ row.resultAdjustedTime | dashEmpty }}</div>
    <a :href="getRaceUrl(row.meetingId,row.raceId)" class="GreyhoundRow__raceLink">
      <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </a>
  </div>
</template>
