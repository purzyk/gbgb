<script>
import Vue from "vue";
import DatePicker from "vue2-datepicker";
import ResultsApi from "../../services/ResultsApi";
import $ from "jquery";
var moment = require("moment");

export default {
  props: ["dogId"],
  components: { DatePicker },
  created() {
    this.getClasses();
    this.getDistances();
  },
  mounted() {
    //Prevent displaying screen keyboard on mobiles for datepicker
    $(".mx-input-wrapper input").attr("readonly", "readonly");
  },
  data() {
    return {
      classes: [],
      distances: [],
      raceDate: "",
      raceClass: "",
      distance: "",
      raceType: "race",
      showPopup: false,
      searchIcon: window.__GBGB_GLOBAL__.icons.search,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross
    };
  },
  methods: {
    getClasses() {
      ResultsApi.getGreyhoundClasses(this.dogId).then(response => {
        this.classes = response.map(element => {
          return { value: element, label: element };
        });
      });
    },
    getDistances() {
      ResultsApi.getGreyhoundDistances(this.dogId).then(response => {
        this.distances = response.map(element => {
          return { value: element, label: element };
        });
      });
    },
    toggleRaceType() {
      this.raceType = this.raceType === "race" ? "both" : "race";
    },
    clear() {
      this.raceDate = "";
      this.raceClass = "";
      this.distance = "";
      this.raceType = "race";
      this.search();
    },
    search() {
      this.$emit("filter", this.buildFilterArray());
      this.showPopup = false;
    },
    buildFilterArray() {
      let filters = [];
      if (
        this.raceDate &&
        this.raceDate[0] !== null &&
        this.raceDate[1] !== null
      ) {
        let toDate = moment(this.raceDate[1]);
        let fromDate = moment(this.raceDate[0]);
        filters.push({ name: "to", value: toDate.format("YYYY-MM-DD") });
        filters.push({ name: "from", value: fromDate.format("YYYY-MM-DD") });
      }
      if (this.distance) {
        filters.push({ name: "distance", value: this.distance });
      }
      if (this.raceClass) {
        filters.push({ name: "class", value: this.raceClass });
      }
      if (!this.raceClass && this.raceType && this.raceType !== "both") {
        filters.push({ name: "race_type", value: this.raceType });
      }
      return filters;
    }
  }
};
</script>

<template>
  <div class="GreyhoundFilters">
    <button type="button" class="GreyhoundFilters__simplified Button" @click="showPopup=true">
      <span v-html="searchIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      <span class="Meta">Search results</span>
    </button>
    <div
      class="GreyhoundFilters__wrapper"
      :class="{'GreyhoundFilters__wrapper--showPopup' : showPopup}"
    >
      <div class="GreyhoundFilters__complex">
        <div class="GreyhoundFiltersMobileHeader">
          <div class="GreyhoundFiltersMobileHeader__title">Search Races</div>
          <button
            type="button"
            class="GreyhoundFiltersMobileHeader__close Button"
            @click="showPopup=false"
          >
            <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </button>
        </div>
        <span class="GreyhoundFilters__dateLabel">Date to/from</span>
        <date-picker
          class="GreyhoundFilters__date"
          :class="{'GreyhoundFilters__date--hasText' : raceDate}"
          placeholder="Select..."
          range
          range-separator=" - "
          format="DD/MM/YY"
          value-type="timestamp"
          v-model="raceDate"
          lang="en"
          :shortcuts="false"
        ></date-picker>
        <animated-select
          v-model="raceClass"
          class="GreyhoundFilters__class"
          placeholder="Class"
          :options="classes"
          emptyLabel="All"
        ></animated-select>
        <animated-select
          v-model="distance"
          class="GreyhoundFilters__distance"
          placeholder="Distance"
          :options="distances"
          emptyLabel="All"
        ></animated-select>
        <div
          class="GreyhoundFilters__trials"
          :class="{'GreyhoundFilters__trials--selected' : raceType === 'both'}"
        >
          <p class="GreyhoundFilters__trials__text">Include trials</p>
          <div class="GreyhoundFilters__trials__checkbox" @click="toggleRaceType">&check;</div>
        </div>
        <div class="GreyhoundFilters__actions">
          <button
            type="button"
            @click="clear"
            class="GreyhoundFilters__buttonClear Button Button--primary__outline"
          >Clear</button>
          <button
            type="button"
            @click="search"
            class="GreyhoundFilters__button Button Button--primary"
          >Search</button>
        </div>
      </div>
    </div>
  </div>
</template>
