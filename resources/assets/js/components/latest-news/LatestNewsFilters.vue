<script>
import Vue from "vue";
var moment = require("moment");

export default {
  props: ["categories", "contentTypes", "startingyear"],
  data() {
    return {
      months: this.getMonths(),
      years: this.getYears(),
      activeFilters: {
        category: [],
        contentType: [],
        month: [],
        year: []
      },
      displayedFilters: [],
      resetAnimation: false,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross,
      resetIcon: window.__GBGB_GLOBAL__.icons.reset
    };
  },
  methods: {
    loadDefaultCategory(defaultCategoryObject) {
      this.$refs.category.addFilter(defaultCategoryObject);
    },
    getYears() {
      let years = [];
      let year = this.startingyear;
      const currentYear = parseInt(moment().format("YYYY"), 10);
      while (year <= currentYear) {
        years.push({
          value: year,
          label: year
        });
        year++;
      }
      return years;
    },
    getMonths() {
      return moment.months().map((monthName, index) => {
        return {
          value: index + 1,
          label: monthName
        };
      });
    },
    removeFilter(filterObject) {
      this.$refs[filterObject.type].removeFilter(filterObject);
    },
    reset() {
      this.resetAnimation = true;
      this.$refs.category.removeAllFilters();
      this.$refs.contentType.removeAllFilters();
      this.$refs.month.removeAllFilters();
      this.$refs.year.removeAllFilters();
    },
    updateFilters(e) {
      this.activeFilters[e.type] = e.filters;
      if (e.type === "year" && e.filters.length === 0) {
        this.$refs.month.removeAllFilters();
      }
      if (
        this.activeFilters.year.length === 0 &&
        this.activeFilters.month.length > 0
      ) {
        this.$refs.year.addFilter({
          label: parseInt(moment().format("YYYY"), 10),
          value: parseInt(moment().format("YYYY"), 10),
          type: "year"
        });
        return;
      }
      let filters = [];
      filters = filters.concat(this.activeFilters.category);
      filters = filters.concat(this.activeFilters.contentType);
      filters = filters.concat(this.activeFilters.month);
      filters = filters.concat(this.activeFilters.year);
      this.displayedFilters = filters;
      this.$emit("change", this.buildFilterArray());
    },
    buildFilterArray() {
      let filters = {};

      const categories = this.activeFilters.category.map(element => {
        return element.value;
      });
      const contentTypes = this.activeFilters.contentType.map(element => {
        return element.value;
      });
      const months = this.activeFilters.month.map(element => {
        return element.value;
      });
      const years = this.activeFilters.year.map(element => {
        return element.value;
      });
      if (categories.length > 0) {
        filters.categories = categories;
      }
      if (contentTypes.length > 0) {
        filters.content_types = contentTypes;
      }
      if (months.length > 0) {
        filters.months = months;
      }
      if (years.length > 0) {
        filters.years = years;
      }
      return filters;
    },
    handleExtended(e) {
      if (e.isExtended === true) {
        this.$refs.category.setExtended(e.type === "category");
        this.$refs.contentType.setExtended(e.type === "contentType");
        this.$refs.month.setExtended(e.type === "month");
        this.$refs.year.setExtended(e.type === "year");
      }
    }
  }
};
</script>

<template>
  <div class="LatestNewsFilters">
    <div class="LatestNewsFilters__header">
      <a
        class="LatestNewsFilters__reset"
        :class="{'LatestNewsFilters__reset--animate' : resetAnimation}"
        @click="reset"
        @animationend="resetAnimation=false"
      >
        <span v-html="resetIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        <span class="Meta">Reset Filters</span>
      </a>
    </div>
    <div class="LatestNewsFilters__selects">
      <latest-news-filter-select
        ref="category"
        type="category"
        :options="categories"
        @change="updateFilters($event)"
        @switchExtended="handleExtended"
        title="Theme"
      />
      <latest-news-filter-select
        ref="contentType"
        type="contentType"
        :options="contentTypes"
        @change="updateFilters($event)"
        @switchExtended="handleExtended"
        title="Type"
      />
      <latest-news-filter-select
        ref="month"
        type="month"
        :options="months"
        @change="updateFilters($event)"
        @switchExtended="handleExtended"
        title="Date"
      />
      <latest-news-filter-select
        ref="year"
        type="year"
        :options="years"
        @change="updateFilters($event)"
        @switchExtended="handleExtended"
        title="Year"
      />
    </div>
    <div class="LatestNewsFilters__active">
      <button
        type="button"
        class="LatestNewsFilter Button Button--white__outline"
        v-for="(filter,index) in displayedFilters"
        :key="`filter-${index}`"
        @click="removeFilter(filter)"
      >
        <span class="Meta">{{ filter.label }}</span>
        <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</template>
