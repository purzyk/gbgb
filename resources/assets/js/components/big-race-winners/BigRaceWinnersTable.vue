<script>
import Vue from "vue";
var moment = require("moment");

export default {
  props: ["isLoading", "rows"],
  data() {
    return {
      sortBy: false,
      sortOrder: "asc",
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron,
      activeButton: "right"
    };
  },
  methods: {
    sort(sortBy) {
      if (this.sortBy === sortBy && this.sortOrder === "desc") {
        this.sortBy = false;
      } else if (this.sortBy === sortBy && this.sortOrder === "asc") {
        this.sortOrder = "desc";
      } else {
        this.sortBy = sortBy;
        this.sortOrder = "asc";
      }
      this.$emit("sort", { sortBy: this.sortBy, sortOrder: this.sortOrder });
    },
    handleScroll() {
      const value = $(this.$refs.table).scrollLeft();
      if (
        value >=
        this.$refs.table.scrollWidth - this.$refs.table.clientWidth
      ) {
        this.activeButton = "left";
      }
      if (value <= 0) {
        this.activeButton = "right";
      }
    },
    scrollToEnd() {
      $(this.$refs.table).scrollLeft(
        this.$refs.table.scrollWidth - this.$refs.table.clientWidth
      );
    },
    scrollToStart() {
      $(this.$refs.table).scrollLeft(0);
    }
  }
};
</script>

<template>
  <div class="BigRaceWinners__wrapper">
    <div
      class="BigRaceWinners__scrollButton BigRaceWinners__scrollButton--left"
      :class="{'BigRaceWinners__scrollButton--active' : activeButton === 'left'}"
      @click="scrollToStart"
    >
      <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
    <div
      ref="table"
      class="BigRaceWinnersTable"
      :class="{'BigRaceWinnersTable--isLoading': isLoading,'BigRaceWinnersTable--sortAsc': sortOrder === 'asc'}"
      @scroll="handleScroll"
    >
      <div class="BigRaceWinnersHeader">
        <div
          @click="sort('bigRaceWinnerRace')"
          :class="{'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerRace'}"
          class="BigRaceWinnersHeader__race BigRaceWinnersHeader__item"
        >
          <span class="Meta">Race</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerTrack')"
          :class="{'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerTrack'}"
          class="BigRaceWinnersHeader__track BigRaceWinnersHeader__item"
        >
          <span class="Meta">Track</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerYear')"
          :class="{ 'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerYear'}"
          class="BigRaceWinnersHeader__year BigRaceWinnersHeader__item"
        >
          <span class="Meta">Year</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerDistance')"
          :class="{ 'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerDistance'}"
          class="BigRaceWinnersHeader__distance BigRaceWinnersHeader__item"
        >
          <span class="Meta">Distance</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerName')"
          :class="{ 'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerName'}"
          class="BigRaceWinnersHeader__name BigRaceWinnersHeader__item"
        >
          <span class="Meta">Name</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerSire')"
          :class="{'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerSire'}"
          class="BigRaceWinnersHeader__sire BigRaceWinnersHeader__item"
        >
          <span class="Meta">Sire</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerDam')"
          :class="{'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerDam'}"
          class="BigRaceWinnersHeader__dam BigRaceWinnersHeader__item"
        >
          <span class="Meta">Dam</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerTrainer')"
          :class="{'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerTrainer'}"
          class="BigRaceWinnersHeader__trainer BigRaceWinnersHeader__item"
        >
          <span class="Meta">Trainer</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerTime')"
          :class="{'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerTime'}"
          class="BigRaceWinnersHeader__time BigRaceWinnersHeader__item"
        >
          <span class="Meta">Time</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('bigRaceWinnerSP')"
          :class="{'BigRaceWinnersHeader__item--sortBy': sortBy === 'bigRaceWinnerSP'}"
          class="BigRaceWinnersHeader__sp BigRaceWinnersHeader__item"
        >
          <span class="Meta">SP</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
      </div>
      <spinner :isActive="isLoading"/>
      <big-race-winners-row :key="`row-${index}`" v-for="(row,index) in rows" :data="row"></big-race-winners-row>
    </div>
    <div
      class="BigRaceWinners__scrollButton BigRaceWinners__scrollButton--right"
      :class="{'BigRaceWinners__scrollButton--active' : activeButton === 'right'}"
      @click="scrollToEnd"
    >
      <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
  </div>
</template>
