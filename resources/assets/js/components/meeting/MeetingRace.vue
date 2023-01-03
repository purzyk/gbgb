<script>
import Vue from "vue";
import $ from "jquery";
import Utils from "../../utils/index";

export default {
  props: ["race", "track", "nonce"],
  mounted() {},
  data() {
    return {
      activeButton: "right",
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron,
      disableScrollHandler: false
    };
  },
  methods: {
    formatTime(time) {
      return time.substr(0, 5);
    }
  },
  filters: {
    filterGoing(string) {
      if (!string) {
        return "-";
      }
      return string;
    }
  },
  computed: {
    displayedTraps() {
      //Remove non runners
      let traps = this.race.traps;
      const filteredTraps = traps.filter(element => {
        return element.resultPosition;
      });

      if (filteredTraps.length > 0) {
        traps = filteredTraps;
      }

      //Add cumulative disctance to 1st position
      traps = traps.map(trap => {
        if (trap.resultPosition === 1) {
          let secondTrap = traps.find(trap => {
            return trap.resultPosition === 2;
          });
          if (secondTrap) {
            trap.resultBtnDistance = secondTrap.resultBtnDistance;
          }
        }
        return trap;
      });

      //Sort by position
      return traps.sort((a, b) => {
        const aPos = a.resultPosition ? a.resultPosition : 99;
        const bPos = b.resultPosition ? b.resultPosition : 99;
        return aPos - bPos;
      });
    },
    raceType() {
      return this.race.raceType === "Hurdles" ? "H" : "";
    }
  }
};
</script>

<template>
  <div class="MeetingRace">
    <div class="MeetingRace__header">
      <div class="MeetingRace__time">{{formatTime(race.raceTime)}}</div>
      <div class="MeetingRace__track">{{track | longTrackName}}</div>
      <div class="MeetingRace__class">| {{race.raceClass}} |</div>
      <div class="MeetingRace__distance">{{race.raceDistance}}m{{raceType}}</div>
      <div class="MeetingRace__prizes">{{race.racePrizes}}</div>
      <div class="MeetingRace__title">{{race.raceTitle}}</div>
    </div>
    <div class="MeetingRace__wrapper">
      <div class="MeetingRaceTableHeader">
        <div class="MeetingRaceTableHeader__trap">Pos,Trap</div>
        <div class="MeetingRaceTableHeader__greyhound">Greyhound</div>
        <div class="MeetingRaceTableHeader__trainer">Trainer</div>
        <div class="MeetingRaceTableHeader__comment">Comment</div>
        <div class="MeetingRaceTableHeader__sp">SP</div>
        <div class="MeetingRaceTableHeader__timeS">Time (S)</div>
        <div class="MeetingRaceTableHeader__timeDistance">Time (Distance)</div>
        <div class="MeetingRaceTableHeader__addToKennel">Add to Kennel</div>
      </div>
      <div class="MeetingRace__table">
        <meeting-race-trap
          :nonce="nonce"
          :key="`trap-${index}`"
          v-for="(trap,index) in displayedTraps"
          :trap="trap"
          :handicap="race.raceHandicap"
        />
      </div>
    </div>
    <div class="MeetingRace__footer">
      <div class="MeetingRace__allowance">
        <strong>Going Allowance:</strong>
        {{race.raceGoing | filterGoing}}
      </div>
      <div class="MeetingRace__forecast">
        <strong>Forecast:</strong>
        {{race.raceForecast | dashEmpty}}
      </div>
      <div class="MeetingRace__tricast">
        <strong>Tricast:</strong>
        {{race.raceTricast | dashEmpty}}
      </div>
    </div>
  </div>
</template>
