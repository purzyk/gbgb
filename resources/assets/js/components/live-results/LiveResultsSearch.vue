<script>
import Vue from "vue";
import DatePicker from "vue2-datepicker";
import ResultsApi from "../../services/ResultsApi";
import $ from "jquery";
import Utils from "../../utils/index";
var moment = require("moment");

export default {
  props: ["pageUrl", "goToPage"],
  components: { DatePicker },
  created() {
    this.getCourses();
    this.getClasses();
  },
  mounted() {
    //Prevent displaying screen keyboard on mobiles for datepicker
    $(".mx-input-wrapper input").attr("readonly", "readonly");
  },
  data() {
    return {
      raceClass: "",
      trackName: "",
      greyhoundName: "",
      greyhoundId: "",
      raceDate: "",
      raceType: "race",
      courses: [],
      classes: [],
      greyhoundAutocomplete: [],
      showPopup: false,
      searchIcon: window.__GBGB_GLOBAL__.icons.search,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross,
      findGrehoundFormCTA: window.__GBGB_GLOBAL__.cta.findGrehoundFormCTA,
      raceTypes: [
        { value: "both", label: "Both" },
        { value: "race", label: "Races" },
        { value: "trial", label: "Trials" }
      ]
    };
  },
  methods: {
    getCourses() {
      ResultsApi.getTrackNames().then(response => {
        response = response.map(element => {
          let label = element.trackName;
          const long = window.__GBGB_GLOBAL__.trackNamesFilters.find(value => {
            return value.replace === label;
          });
          if (long) {
            label = long.with;
          }
          return { value: element.trackName, label: label };
        });
        response.sort(function(a, b) {
          if (a.label < b.label) {
            return -1;
          }
          if (a.label > b.label) {
            return 1;
          }
          return 0;
        });
        this.courses = response;
      });
    },
    getClasses() {
      ResultsApi.getClasses().then(response => {
        response = response.filter(element => {
          return element.class !== "";
        });
        response = response.map(element => {
          let label = element.class;
          return { value: element.class, label: label };
        });
        response.sort(function(a, b) {
          if (a.label < b.label) {
            return -1;
          }
          if (a.label > b.label) {
            return 1;
          }
          return 0;
        });
        this.classes = response;
      });
    },
    clear() {
      this.raceClass = "";
      this.trackName = "";
      this.greyhoundName = "";
      this.greyhoundId = "";
      this.raceDate = "";
      this.raceType = "race";
      if (!this.goToPage) {
        this.search();
      }
    },
    search() {
      let url = Utils.buildUrl(this.pageUrl, {
        raceClass: this.raceClass,
        trackName: this.trackName,
        greyhoundName: this.greyhoundName,
        greyhoundId: this.greyhoundId,
        raceDate: this.raceDate
          ? moment(this.raceDate).format("YYYY-MM-DD")
          : "",
        raceType: this.raceType
      });
      if (this.goToPage) {
        window.location.href = url;
      } else {
        window.history.pushState({}, "", url);
        this.$emit("filter", this.buildFilterArray());
        this.showPopup = false;
      }
    },
    buildFilterArray() {
      let filters = [];
      if (this.raceClass) {
        filters.push({ name: "class", value: this.raceClass });
      }
      if (this.trackName) {
        filters.push({ name: "track", value: this.trackName });
      }
      if (this.greyhoundId) {
        filters.push({ name: "dog_id", value: this.greyhoundId });
      }
      if (this.raceDate) {
        let returnDate = moment(this.raceDate);
        filters.push({ name: "date", value: returnDate.format("YYYY-MM-DD") });
      }
      if (this.raceType !== "both") {
        filters.push({ name: "race_type", value: this.raceType });
      }
      return filters;
    },
    loadFilter(filters) {
      this.raceClass = filters.raceClass;
      this.trackName = filters.trackName;
      this.greyhoundName = filters.greyhoundName;
      this.greyhoundId = filters.greyhoundId;
      this.raceDate = filters.raceDate;
      this.raceType = filters.raceType ? filters.raceType : "race";
      this.search();
    },
    handleGreyhoundChange(e) {
      this.greyhoundName = e.greyhoundName;
      this.greyhoundId = e.greyhoundId;
    }
  },
  computed: {
    parametersArray() {
      return [this.trackName, this.raceClass, this.raceType, this.raceDate];
    }
  },
  watch: {
    parametersArray(newVal, oldVal) {
      if (!this.goToPage && !this.showPopup) {
        this.search();
      }
    }
  }
};
</script>

<template>
  <div class="LiveResultsSearch">
    <button type="button" class="LiveResultsSearch__simplified Button" @click="showPopup=true">
      <span v-html="searchIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      <span class="Meta">Search results</span>
    </button>
    <div
      class="LiveResultsSearch__wrapper"
      :class="{'LiveResultsSearch__wrapper--showPopup' : showPopup}"
    >
      <div class="LiveResultsSearch__complex">
        <div class="LiveResultsSearchMobileHeader">
          <div class="LiveResultsSearchMobileHeader__title">Search Races</div>
          <button
            type="button"
            aria-label="Close search modal"
            class="LiveResultsSearchMobileHeader__close Button"
            @click="showPopup=false"
          >
            <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </button>
        </div>
        <animated-select
          v-model="trackName"
          class="LiveResultsSearch__track"
          placeholder="Track"
          :options="courses"
          emptyLabel="All"
        ></animated-select>
        <animated-select
          v-model="raceClass"
          class="LiveResultsSearch__class"
          placeholder="Class"
          :options="classes"
          emptyLabel="All"
        ></animated-select>
        <date-picker
          class="LiveResultsSearch__date"
          :class="{'LiveResultsSearch__date--hasText' : raceDate}"
          format="DD/MM/YY"
          value-type="timestamp"
          v-model="raceDate"
          lang="en"
          placeholder="Date"
        ></date-picker>
        <div class="LiveResultsSearch__race_types">
          <animated-select
            v-model="raceType"
            class="LiveResultsSearch__race_types_select"
            placeholder="Race type"
            :options="raceTypes"
            :disableEmpty="true"
          ></animated-select>
        </div>
        <div class="LiveResultsSearch__actions">
          <button
            type="button"
            @click="clear"
            class="LiveResultsSearch__buttonClear Button Button--primary__outline"
          >Clear</button>
          <button
            type="button"
            @click="search"
            class="LiveResultsSearch__button Button Button--primary"
          >Search</button>
        </div>
        <find-a-greyhound mode="search" :cta="findGrehoundFormCTA"></find-a-greyhound>
      </div>
    </div>
  </div>
</template>
