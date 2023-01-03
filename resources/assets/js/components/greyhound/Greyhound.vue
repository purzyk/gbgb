<script>
import Vue from "vue";
import ResultsApi from "../../services/ResultsApi";
import MyDogsApi from "../../services/MyDogsApi";
var moment = require("moment");
import Utils from "../../utils/index";

export default {
  props: ["dogid", "nonce"],
  created() {
    this.getGreyhound();
    this.getResults();
  },
  data() {
    return {
      dog: null,
      rows: [],
      isLoading: true,
      isSaving: false,
      page: 1,
      itemsPerPage: 20,
      pageCount: 0,
      count: 0,
      filters: [{ name: "race_type", value: "race" }],
      sortBy: false,
      sortOrder: "desc",
      resultsPageUrl: window.__GBGB_GLOBAL__.pages.results,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow,
      starIcon: window.__GBGB_GLOBAL__.icons.star,
      isFaved:
        window.__GBGB_GLOBAL__.myDogs.findIndex(element => {
          return parseInt(element.dogId, 10) === parseInt(this.dogid, 10);
        }) > -1,
      modal: false
    };
  },
  methods: {
    update() {
      this.isLoading = true;
      this.getGreyhound();
      this.getResults();
    },
    getGreyhound() {
      ResultsApi.getGreyhound(this.dogid).then(response => {
        this.dog = response;
        this.isLoading = false;
      });
    },
    getResults() {
      let filters = this.filters.slice();
      if (this.sortBy) {
        filters.push({ name: "sort_by", value: this.sortBy });
        filters.push({ name: "sort_dir", value: this.sortOrder });
      }
      ResultsApi.getGreyhoundResults(
        this.dogid,
        this.page,
        this.itemsPerPage,
        filters
      ).then(response => {
        this.rows = response.items;
        this.count = response.meta.count;
        this.pageCount = response.meta.pageCount;
        this.isLoading = false;
      });
    },
    updateFilter(e) {
      this.page = 1;
      this.filters = e;
      this.update();
    },
    updatePagination(e) {
      this.page = e.page;
      this.itemsPerPage = e.itemsPerPage;
      this.update();
    },
    updateSorting(e) {
      this.page = 1;
      this.sortBy = e.sortBy;
      this.sortOrder = e.sortOrder;
      this.update();
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
      MyDogsApi.addDog(this.nonce, this.dogid).then(response => {
        Utils.showNotification("You have added a greyhound to your kennel");
        this.isSaving = false;
        this.isFaved = true;
      });
    },
    deleteDog() {
      this.modal = false;
      this.isSaving = true;
      MyDogsApi.removeDog(this.nonce, this.dogid).then(response => {
        Utils.showNotification("You have removed a greyhound from your kennel");
        this.isSaving = false;
        this.isFaved = false;
      });
    },
    hideModal() {
      this.modal = false;
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
  }
};
</script>

<template>
  <section class="Greyhound">
    <div class="Greyhound__header" v-if="dog">
      <div class="Greyhound__header__details">
        <p class="Greyhound__header__trainer">Trainer: {{dog.trainerName}}</p>
        <div class="Greyhound__header__title">
          <a :href="resultsPageUrl" aria-label="Back to previous page" class="Greyhound__header__title__back">
            <span v-html="arrowIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </a>
          <h1 class="Greyhound__header__title__meta">{{ dog.dogName }}</h1>
        </div>
        <p class="Greyhound__header__stats">{{dog.dogSire}} - {{dog.dogDam}} | {{dog.dogSex}} - {{dog.dogColour}} | {{dog.dogBorn}} | {{dog.dogSeason | dashEmpty}}</p>
      </div>
      <button
        type="button"
        class="Greyhound__button Button Button--white__outline"
        :class="{'Greyhound__button--savingState': isSaving, 'Greyhound__button--saved': isFaved }"
        @click="handleButton"
      >
        <span v-if="!isFaved" v-html="starIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        <span v-else v-html="starIcon" class="Icon Icon--starred SvgContainer" aria-hidden="true"></span>
        <span v-if="!isFaved" class="Meta">Add to favourites</span>
        <span v-else class="Meta">Remove favourite</span>
      </button>
    </div>
    <div class="Greyhound__inner">
      <greyhound-filters :dogId="dogid" @filter="updateFilter"/>
      <greyhound-table :rows="rows" :isLoading="isLoading" @sort="updateSorting"/>
      <live-results-pagination
        :page="page"
        :itemsPerPage="itemsPerPage"
        :count="count"
        :pageCount="pageCount"
        :isLoading="isLoading"
        @pagination="updatePagination"
      />
    </div>
    <popup-delete v-if="modal" :yesMethod="deleteDog" :noMethod="hideModal"/>
    <popup-login ref="popupLogin"></popup-login>
  </section>
</template>
