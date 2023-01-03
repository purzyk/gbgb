<script>
import Vue from "vue";
import ResultsApi from "../../services/ResultsApi";
import MyDogsApi from "../../services/MyDogsApi";
import Utils from "../../utils/index";
var moment = require("moment");

export default {
  props: ["data", "nonce"],
  created() {
    this.$emit("requestLoad", { callback: this.getGreyhound });
  },
  data() {
    return {
      isLoading: true,
      isFailed: false,
      dog: [],
      greyhoundPageUrl: window.__GBGB_GLOBAL__.pages.greyhound,
      notes: this.data.dogNotes,
      notification: this.data.dogNotification,
      isExtended: false,
      isSaving: false,
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron,
      starIcon: window.__GBGB_GLOBAL__.icons.star,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross,
      showModal: false,
      fixtures: []
    };
  },
  methods: {
    getGreyhound() {
      ResultsApi.getGreyhound(this.data.dogId).then(response => {
        if (!response) {
          this.isFailed = true;
          this.$emit("failed", {});
          return;
        }
        this.dog = response;
        ResultsApi.getGreyhoundFixtures(this.data.dogId).then(response => {
          this.fixtures = response.filter(element => {
            const d = moment(element.meetingDate, "DD/MM/YYYY");
            return moment(d).isSame(moment(), "day");
          });
          this.isLoading = false;
          this.$emit("loaded", {});
        });
      });
    },
    getDogUrl(dogId) {
      return this.greyhoundPageUrl + "?greyhoundId=" + dogId;
    },
    toggle() {
      if (this.isLoading || this.isSaving) {
        return;
      }
      if (this.isExtended) {
        $(this.$refs.inner).slideUp();
      } else {
        $(this.$refs.inner).slideDown({
          start: function() {
            $(this).css({
              display: "flex"
            });
          }
        });
      }
      this.isExtended = !this.isExtended;
      this.$emit("toggled", { isExtended: this.isExtended, component: this });
    },
    switchNotification() {
      this.notification = !this.notification;
      this.isSaving = true;
      MyDogsApi.editDog(
        this.nonce,
        this.data.dogId,
        null,
        this.notification
      ).then(response => {
        this.isSaving = false;
      });
    },
    saveNotes() {
      this.isSaving = true;
      MyDogsApi.editDog(
        this.nonce,
        this.data.dogId,
        this.notes,
        this.notification
      ).then(response => {
        this.isSaving = false;
      });
    },
    deleteDog() {
      this.showModal = false;
      this.isSaving = true;
      MyDogsApi.removeDog(this.nonce, this.data.dogId).then(response => {
        Utils.showNotification("You have removed a greyhound from your kennel");
        this.$emit("deleted", { dogId: this.data.dogId });
      });
    },
    formatTime(time) {
      return time.substr(0, 5);
    }
  }
};
</script>

<template>
  <div class="Item" :class="{'Item--isExtended':isExtended}">
    <div class="Item__bar" @click="toggle" v-if="!isFailed">
      <p class="Item__bar__title">
        <spinner :isActive="isLoading && !isExtended"/>
        {{ dog.dogName }}
      </p>
      <p class="Item__bar__formLink">
        <a @click.stop :href="getDogUrl(dog.dogId)" v-if="!isLoading">Greyhound form</a>
      </p>
      <button type="button" aria-label="Expand/toggle item" class="Item__bar__button Button">
        <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </button>
      <ul class="Item__bar__fixtures" v-if="fixtures.length>0">
        <li class="DogFixture" v-for="(fixture,index) in fixtures" :key="`fixture-${index}`">
          <div class="DogFixture__label">Racing today:</div>
          <div v-if="fixture.raceTime" class="DogFixture__time">{{formatTime(fixture.raceTime)}}</div>
          <div
            v-if="fixture.trackName"
            class="DogFixture__track"
          >{{fixture.trackName | longTrackName}}</div>
          <div v-if="fixture.raceNumber" class="DogFixture__race">Race {{fixture.raceNumber}}</div>
        </li>
      </ul>
    </div>
    <div class="Item__inner" ref="inner" v-if="!isFailed">
      <div
        class="Item__inner__details"
        v-if="!isLoading"
      >{{dog.dogSex}} {{dog.dogColour}} {{dog.dogSire}} - {{dog.dogDam}} {{dog.dogSeason}}</div>
      <div class="Item__inner__notifications">
        <div
          class="Switch"
          :class="{'Switch--active': notification,'Switch--savingState': isSaving }"
          @click="switchNotification"
        ></div>
        <p class="Item__inner__notifications__text">Notify me by email when this dog is racing</p>
      </div>
      <div class="Item__inner__notes" :class="{'Item__inner__notes--savingState': isSaving }">
        <p class="Item__inner__notes__text">Notes (only you can see these notes)</p>
        <textarea :disabled="isSaving" v-model="notes" v-if="!isLoading"></textarea>
      </div>
      <div class="Item__inner__actions">
        <button
          type="button"
          class="MyKennelButton Button Button--primary"
          :class="{'MyKennelButton--savingState': isSaving }"
          @click="saveNotes"
          v-if="!isLoading"
        >Save</button>
        <button
          type="button"
          class="MyKennelButton__remove MyKennelButton Button Button--primary__outline"
          :class="{'MyKennelButton--savingState': isSaving }"
          v-if="!isLoading"
          @click="showModal=true"
        >
          <span v-html="starIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          <span class="Meta">Remove dog</span>
        </button>
      </div>
      <spinner :isActive="isLoading && isExtended"/>
    </div>
    <div class="MyKennelModal" v-if="showModal">
      <div class="MyKennelModal__inner">
        <button
          type="button"
          aria-label="Close modal"
          class="MyKennelModal__close Button"
          @click="showModal=false"
        >
          <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </button>
        <p class="MyKennelModal__title">Remove Greyhound</p>
        <p
          class="MyKennelModal__text"
        >Are you sure you want to remove this greyhound from your kennel?</p>
        <div class="MyKennelModal__actions MyKennelModal__row">
          <button
            type="button"
            class="MyKennelModal__row__button MyKennelButton Button Button--primary__outline"
            @click.stop="showModal=false"
          >No</button>
          <button
            type="button"
            class="MyKennelModal__row__button MyKennelButton Button Button--primary"
            @click="deleteDog"
          >Yes</button>
        </div>
      </div>
    </div>
  </div>
</template>
