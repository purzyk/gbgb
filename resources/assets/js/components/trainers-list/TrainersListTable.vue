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
  <div class="TrainersList__wrapper">
    <div
      class="TrainersList__scrollButton TrainersList__scrollButton--left"
      :class="{'TrainersList__scrollButton--active' : activeButton === 'left'}"
      @click="scrollToStart"
    >
      <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
    <div
      ref="table"
      class="TrainersListTable"
      :class="{'TrainersListTable--isLoading': isLoading,'TrainersListTable--sortAsc': sortOrder === 'asc'}"
      @scroll="handleScroll"
    >
      <div class="TrainersListHeader">
        <div
          @click="sort('trainerFullname')"
          :class="{'TrainersListHeader__item--sortBy': sortBy === 'trainerFullname'}"
          class="TrainersListHeader__fullname TrainersListHeader__item"
        >
          <span class="Meta">Name</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('trainerTitle')"
          :class="{'TrainersListHeader__item--sortBy': sortBy === 'trainerTitle'}"
          class="TrainersListHeader__title TrainersListHeader__item"
        >
          <span class="Meta">Title</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('trainerTrackName')"
          :class="{'TrainersListHeader__item--sortBy': sortBy === 'trainerTrackName'}"
          class="TrainersListHeader__trackname TrainersListHeader__item"
        >
          <span class="Meta">Track name</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>

        <div
          @click="sort('trainerLicense')"
          :class="{'TrainersListHeader__item--sortBy': sortBy === 'trainerLicense'}"
          class="TrainersListHeader__license TrainersListHeader__item"
        >
          <span class="Meta">License type</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div
          @click="sort('trainerTown')"
          :class="{'TrainersListHeader__item--sortBy': sortBy === 'trainerTown'}"
          class="TrainersListHeader__town TrainersListHeader__item"
        >
          <span class="Meta">Town</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div class="TrainersListHeader__telno TrainersListHeader__item">
          <span class="Meta">Tel. No.</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
        <div class="TrainersListHeader__mobilephoneno TrainersListHeader__item">
          <span class="Meta">Mobile Tel. No.</span>
          <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </div>
      </div>
      <spinner :isActive="isLoading"/>
      <trainers-list-row :key="`row-${index}`" v-for="(row,index) in rows" :data="row"></trainers-list-row>
    </div>
    <div
      class="TrainersList__scrollButton TrainersList__scrollButton--right"
      :class="{'TrainersList__scrollButton--active' :activeButton === 'right'}"
      @click="scrollToEnd"
    >
      <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
  </div>
</template>
