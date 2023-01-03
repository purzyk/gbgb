<script>
import Vue from "vue";
var moment = require("moment");
import Utils from "../../utils/index";
import ResultsApi from "../../services/ResultsApi";

export default {
  props: ["row", "isRepeatedDate"],
  data() {
    return {
      greyhoundPageUrl: window.__GBGB_GLOBAL__.pages.greyhound,
      resultsPageUrl: window.__GBGB_GLOBAL__.pages.meeting,
      isLoading: false,
      missingData: false,
      meetingId: 0,
      greyhodundId: 0,
      calendarIcon: window.__GBGB_GLOBAL__.icons.calendar,
      calendarTypesVisible: false
    };
  },
  created() {
    if (this.isPast(this.row.RaceStageDate)) {
      this.isLoading = true;
    }
  },
  mounted() {
    Utils.inView(this.$el, "TransitionFadeIn");
  },
  methods: {
    loadMeetingData() {
      ResultsApi.getRace(this.row.RaceID).then(response => {
        if (!response) {
          this.missingData = true;
          this.isLoading = false;
          return;
        }
        this.meetingId = response[0].meetingId;
        const winnerTrap = response[0].races[0].traps.find(trap => {
          return (trap.resultPosition = 1);
        });
        if (winnerTrap) {
          this.greyhodundId = winnerTrap.dogId;
        }
        this.greyhodundName = winnerTrap.dogName;
        this.isLoading = false;
      });
    },
    getIcal(row, mime = "text/plain") {
      let cal = `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//GBGB//Open Race//EN
NAME:${row.RaceName}
X-WR-CALNAME:${row.RaceName}
TIMEZONE-ID:Europe/London
X-WR-TIMEZONE:Europe/London
BEGIN:VEVENT
UID:k8so@http://www.gbgb.org.uk/.net
SEQUENCE:0
DTSTAMP:${moment(row.RaceStageDate)
        .add(8, "hour")
        .format("YYYYMMDDTHHmmss")}
DTSTART:${moment(row.RaceStageDate)
        .add(8, "hour")
        .format("YYYYMMDDTHHmmss")}
DTEND:${moment(row.RaceStageDate)
        .add(24, "hour")
        .format("YYYYMMDDTHHmmss")}
SUMMARY:${row.RaceName}
ORGANIZER;CN="GBGB":mailto:thewebsite@thedogs.co.uk
END:VEVENT
END:VCALENDAR`;
      Utils.downloadFile(row.RaceName + ".ics", cal, mime);
      this.calendarTypesVisible = false;
    },
    getGoogleCalLink(row) {
      const startDate = moment(row.RaceStageDate).format("YYYYMMDD");
      const endDate = moment(row.RaceStageDate)
        .add(24, "hour")
        .format("YYYYMMDD");
      return Utils.buildUrl(
        "https://calendar.google.com/calendar/r/eventedit",
        {
          text: row.RaceName,
          dates: startDate + "/" + endDate,
          ctz: "Europe/London"
        }
      );
    },
    isToday(date) {
      return moment(date).isSame(moment(), "day");
    },
    isPast(date) {
      return moment(date).isBefore(moment(), "day");
    },
    getDogUrl() {
      if (this.missingData) {
        return "#";
      }
      return this.greyhoundPageUrl + "?greyhoundId=" + this.greyhodundId;
    },
    getRaceUrl() {
      return (
        this.resultsPageUrl +
        "?meetingId=" +
        this.meetingId +
        "&raceId=" +
        this.row.RaceID
      );
    }
  },
  computed: {
    isClosingDatetimeValid() {
      return (
        moment(this.row.Open_Race_Stage_Closing_Datetime).format("YYYY") > 1900
      );
    }
  },
  filters: {
    formatDate(date) {
      return moment(date).format("dddd Do MMMM YYYY");
    },
    formatEntriesDate(date) {
      return moment(date).format("DD/MM/YYYY");
    },
    formatEntriesTime(date) {
      return moment(date).format("kk:mm");
    }
  }
};
</script>

<template>
  <div class="OpenRacesRow">
    <div
      v-if="!isRepeatedDate"
      class="OpenRacesRow__date"
      :class="{'OpenRacesRow__date--today' : isToday(row.RaceStageDate)}"
    >{{row.RaceStageDate|formatDate}}</div>
    <header class="OpenRacesRow__header">
      <h3 class="OpenRacesRow__raceName">{{row.RaceName}}</h3>
      <div
        type="button"
        class="OpenRacesRow__ical Button"
        @click="calendarTypesVisible=!calendarTypesVisible"
      >
        <span v-html="calendarIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        <div
          class="OpenRacesRow__calendarTypes"
          :class="{'OpenRacesRow__calendarTypes--visible':calendarTypesVisible}"
        >
          <a @click.stop="getIcal(row,'text/calendar')">Apple</a>
          <a
            :href="getGoogleCalLink(row)"
            @click.stop="calendarTypesVisible=false"
            target="_blank"
          >Google</a>
          <a @click.stop="getIcal(row,'text/plain')">Ics</a>
        </div>
      </div>
    </header>
    <div class="OpenRacesRow__comments" v-if="row.RaceStageComments">{{row.RaceStageComments}}</div>
    <div class="OpenRacesRow__tags">
      <span class="OpenRacesRow__tag --trackName">{{row.TrackName}}</span>
      <span class="OpenRacesRow__tag --length">{{row.RaceStageLength}}M</span>
      <span class="OpenRacesRow__tag --raceCategory">{{row.RaceCategory}}</span>
      <span class="OpenRacesRow__tag --greyhoundCategory">{{row.GreyhoundCategory}}</span>
    </div>

    <footer class="OpenRacesRow__footer --isNowandFuture" v-if="!isPast(row.RaceStageDate)">
      <div class="OpenRacesRow__prize1st">1st £{{row.RaceStagePrize1st}}</div>
      <div class="OpenRacesRow__prizeTotal">Total £{{row.RaceStagePrizeTotal}}</div>
      <div
        class="OpenRacesRow__closes"
        v-if="row.Open_Race_Stage_Closing_Datetime && isClosingDatetimeValid"
      >
        Entries closes on
        <strong>{{row.Open_Race_Stage_Closing_Datetime|formatEntriesDate}}</strong> at
        <strong>{{row.Open_Race_Stage_Closing_Datetime|formatEntriesTime}}</strong>
      </div>
    </footer>
    <footer class="OpenRacesRow__footer --isPast" v-if="isPast(row.RaceStageDate) && !isLoading"></footer>
  </div>
</template>
