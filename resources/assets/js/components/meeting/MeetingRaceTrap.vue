<script>
import Vue from "vue";
var moment = require("moment");
import MyDogsApi from "../../services/MyDogsApi";
import Utils from "../../utils/index";

export default {
  props: ["trap", "nonce", "handicap"],
  data() {
    return {
      modal: false,
      isSaving: false,
      starIcon: window.__GBGB_GLOBAL__.icons.star,
      greyhoundPageUrl: window.__GBGB_GLOBAL__.pages.greyhound,
      isFaved:
        window.__GBGB_GLOBAL__.myDogs.findIndex(element => {
          return parseInt(element.dogId, 10) === parseInt(this.trap.dogId, 10);
        }) > -1
    };
  },
  mounted() {},
  methods: {
    getTrapBadge(trapNumber) {
      return window.__GBGB_GLOBAL__.trapBadges["trapBadge" + trapNumber];
    },
    getDogUrl(dogId) {
      return this.greyhoundPageUrl + "?greyhoundId=" + dogId;
    },
    handleButton() {
      if (!window.__GBGB_GLOBAL__.loggedIn) {
        this.$refs.popupLogin.show = true;
        return;
      }
      if (this.isFaved) {
        this.modal = true;
      } else {
        this.addDog();
      }
    },
    addDog() {
      this.modal = false;
      this.isSaving = true;
      MyDogsApi.addDog(this.nonce, this.trap.dogId).then(response => {
        Utils.showNotification("You have added a greyhound to your kennel");
        this.isSaving = false;
        this.isFaved = true;
      });
    },
    deleteDog() {
      this.modal = false;
      this.isSaving = true;
      MyDogsApi.removeDog(this.nonce, this.trap.dogId).then(response => {
        Utils.showNotification("You have removed a greyhound from your kennel");
        this.isSaving = false;
        this.isFaved = false;
      });
    },
    hideModal() {
      this.modal = false;
    },
    getTimeDistance() {
      let result = this.trap.resultRunTime;
      if (this.trap.resultBtnDistance) {
        result += " (" + this.trap.resultBtnDistance + ")";
      }
      return result;
    }
  },
  filters: {
    formatPosition(pos) {
      pos = moment(pos, "D").format("Do");
      return pos !== "Invalid date" ? pos : "";
    }
  },
  computed: {
    timeSValue() {
      return this.handicap
        ? this.trap.trapHandicap
        : this.trap.resultSectionalTime;
    }
  }
};
</script>

<template>
  <div class="MeetingRaceTrap">
    <div class="MeetingRaceTrap__data MeetingRaceTrap__data--desktop">
      <div class="MeetingRaceTrap__upper">
        <div class="MeetingRaceTrap__pos">{{trap.resultPosition | formatPosition}}</div>
        <a :href="getDogUrl(trap.dogId)" class="MeetingRaceTrap__greyhound">{{trap.dogName}}</a>
        <div class="MeetingRaceTrap__trainer">{{trap.trainerName}}</div>
        <div class="MeetingRaceTrap__comment">{{trap.resultComment | dashEmpty}}</div>
        <div class="MeetingRaceTrap__sp">{{trap.SP | dashEmpty}}</div>
        <div class="MeetingRaceTrap__timeS">{{timeSValue | dashEmpty}}</div>
        <div class="MeetingRaceTrap__timeDistance">{{getTimeDistance() | dashEmpty}}</div>
      </div>
      <div class="MeetingRaceTrap__lower">
        <div class="MeetingRaceTrap__trap">
          <img :src="getTrapBadge(trap.trapNumber)" alt />
        </div>
        <div
          class="MeetingRaceTrap__houndProfile"
        >{{trap.dogBorn | dashEmpty}} | {{trap.resultDogWeight | dashEmpty}} | {{trap.dogSex}} - {{trap.dogColour}} | {{trap.dogSire}} - {{trap.dogDam}}</div>
      </div>
    </div>

    <div class="MeetingRaceTrap__data MeetingRaceTrap__data--mobile">
      <div class="MeetingRaceTrap__upper">
        <div class="MeetingRaceTrap__pos">{{trap.resultPosition | formatPosition}}</div>
        <div class="MeetingRaceTrap__trap">
          <img :src="getTrapBadge(trap.trapNumber)" alt />
        </div>
        <a :href="getDogUrl(trap.dogId)" class="MeetingRaceTrap__greyhound">{{trap.dogName}}</a>
        <div class="MeetingRaceTrap__sp">{{trap.SP | dashEmpty}}</div>
      </div>
      <div class="MeetingRaceTrap__line">
        <div class="MeetingRaceTrap__timeS">{{timeSValue | dashEmpty}}</div>
        <div class="MeetingRaceTrap__comment">{{trap.resultComment | dashEmpty}}</div>
        <div class="MeetingRaceTrap__timeDistance">{{getTimeDistance() | dashEmpty}}</div>
      </div>
      <div class="MeetingRaceTrap__line">
        <div class="MeetingRaceTrap__trainer">T: {{trap.trainerName}}</div>
        <div class="MeetingRaceTrap__weight">Weight: {{trap.resultDogWeight | dashEmpty}}</div>
      </div>
      <div
        class="MeetingRaceTrap__line"
      >{{trap.dogBorn | dashEmpty}} | {{trap.dogSex}} - {{trap.dogColour}}</div>
      <div class="MeetingRaceTrap__line">{{trap.dogSire}} - {{trap.dogDam}}</div>
    </div>

    <button
      type="button"
      class="MeetingRaceTrap__addToKennel Button"
      @click="handleButton"
      :class="{'MeetingRaceTrap__addToKennel--savingState': isSaving}"
    >
      <span v-if="!isFaved" v-html="starIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      <span v-else v-html="starIcon" class="Icon Icon--starred SvgContainer" aria-hidden="true"></span>
    </button>

    <popup-delete v-if="modal" :yesMethod="deleteDog" :noMethod="hideModal" />
    <popup-login ref="popupLogin"></popup-login>
  </div>
</template>
