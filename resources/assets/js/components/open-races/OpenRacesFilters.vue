<script>
import Vue from "vue";
import DatePicker from "vue2-datepicker";
import VueSlider from "vue-slider-component";
import $ from "jquery";
import "vue-slider-component/theme/antd.css";
var moment = require("moment");

export default {
  props: ["filterOptions"],
  components: { DatePicker, VueSlider },
  mounted() {
    $(window).on("click", this.maybeCloseDistance);
    $(window).on("touch", this.maybeCloseDistance);
    //Prevent displaying screen keyboard on mobiles for datepicker
    $(".mx-input-wrapper input").attr("readonly", "readonly");
  },
  data() {
    return {
      raceTypes: this.filterOptions.raceTypes,
      tracks: this.filterOptions.tracks,
      distanceMax: parseInt(this.filterOptions.distanceMax, 10),
      categories: this.filterOptions.categories,
      greyhoundCategories: this.filterOptions.greyhoundCategories,
      date: "",
      raceType: "",
      track: "",
      distance: [210, parseInt(this.filterOptions.distanceMax, 10)],
      category: "",
      greyhoundCategory: "",
      distanceExtended: false,
      distanceFocusField: false,
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle
    };
  },
  methods: {
    maybeCloseDistance(e) {
      const container = $(this.$refs.distance);
      // if the target of the click isn't the container nor a descendant of the container
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        this.distanceExtended = false;
      }
    },
    search() {
      this.$emit("filter", this.buildFilterArray());
    },
    clear() {
      this.date = "";
      this.raceType = "";
      this.track = "";
      this.distance = [210, parseInt(this.filterOptions.distanceMax, 10)];
      this.category = "";
      this.greyhoundCategory = "";
      this.$emit("filter", this.buildFilterArray());
    },
    buildFilterArray() {
      let filters = [];
      if (this.date) {
        let returnDate = moment(this.date);
        filters.push({ name: "ondate", value: returnDate.format("YYYYMMDD") });
      }
      if (this.track) {
        filters.push({ name: "track", value: this.track });
      }
      if (this.category) {
        filters.push({
          name: "category",
          value: this.category
        });
      }
      if (this.distance[0] > 1 || this.distance[1] < this.distanceMax) {
        filters.push({
          name: "distancefrom",
          value: this.distance[0]
        });
        filters.push({
          name: "distanceTo",
          value: this.distance[1]
        });
      }
      if (this.greyhoundCategory) {
        filters.push({
          name: "greyhoundcategory",
          value: this.greyhoundCategory
        });
      }
      if (this.raceType) {
        filters.push({
          name: "racetype",
          value: this.raceType
        });
      }
      return filters;
    }
  }
};
</script>

<template>
  <div class="OpenRacesFilters">
    <div class="OpenRacesFilters__date_wrapper">
      <date-picker
        :class="{'OpenRacesFilters__date--hasText' : date}"
        class="OpenRacesFilters__block OpenRacesFilters__date"
        placeholder="Date"
        format="DD/MM/YY"
        value-type="timestamp"
        v-model="date"
        lang="en"
      ></date-picker>
      <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
    <animated-select
      v-model="raceType"
      class="OpenRacesFilters__block OpenRacesFilters__type"
      placeholder="Race Type"
      :options="raceTypes"
      emptyLabel="All"
    ></animated-select>
    <animated-select
      v-model="track"
      class="OpenRacesFilters__block OpenRacesFilters__track"
      placeholder="Track"
      :options="tracks"
      emptyLabel="All"
    ></animated-select>
    <div
      ref="distance"
      value
      :class="{'OpenRacesFilters__distance--hasText' : distanceFocusField}"
      class="OpenRacesFilters__block OpenRacesFilters__distance"
    >
      <div
        class="OpenRacesFilters__distanceButton"
        @click="distanceExtended=!distanceExtended; distanceFocusField = true;"
      >{{distance[0]}}m - {{distance[1]}}m</div>
      <div
        class="OpenRacesFilters__distance__wrapper"
        :class="{'OpenRacesFilters__distance__wrapper--extended' : distanceExtended}"
      >
        <div class="OpenRacesFilters__distance__mobile_label">Distance</div>
        <div class="OpenRacesFilters__distance__numbers">
          <input maxlength="4" class="OpenRacesFilters__distance--min" v-model="distance[0]" />
          <input maxlength="4" class="OpenRacesFilters__distance--max" v-model="distance[1]" />
        </div>
        <vue-slider v-model="distance" :min="210" :max="distanceMax" />
      </div>
    </div>

    <animated-select
      v-model="category"
      class="OpenRacesFilters__block OpenRacesFilters__category"
      placeholder="Category"
      :options="categories"
      emptyLabel="All"
    ></animated-select>
    <animated-select
      v-model="greyhoundCategory"
      class="OpenRacesFilters__block OpenRacesFilters__greyhoundCategory"
      placeholder="Greyhound Category"
      :options="greyhoundCategories"
      emptyLabel="All"
    ></animated-select>
    <div class="OpenRacesFilters__actions">
      <button
        type="button"
        class="OpenRacesFilters__clearAll Button Button--primary__outline"
        @click="clear"
      >Clear</button>
      <button
        type="button"
        @click="search"
        class="OpenRacesFilters__button Button Button--primary"
      >Search</button>
    </div>
  </div>
</template>
