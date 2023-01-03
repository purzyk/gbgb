<script>
import Vue from "vue";
var moment = require("moment");

export default {
  props: ["isLoading", "rows"],
  data() {
    return {
      sortBy: false,
      sortOrder: "asc",
      pageUrl: window.__GBGB_GLOBAL__.pages.meeting,
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron,
      activeButton: "right"
    };
  },
  methods: {
    getTrapBadge(trapNumber) {
      return window.__GBGB_GLOBAL__.trapBadges["trapBadge" + trapNumber];
    },
    getDogUrl(dogId) {
      return this.pageUrl + "?greyhoundId=" + dogId;
    },
    formatDate(date) {
      const d = moment(date, "DD/MM/YYYY");
      if (moment(d).isSame(moment(), "day")) {
        return "Today";
      }
      if (moment(d).isSame(moment().subtract(1, "days"), "day")) {
        return "Yesterday";
      }
      return d.format("DD/MM");
    },
    formatTime(time) {
      return time.substr(0, 5);
    },
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
    }
  }
};
</script>

<template>
  <div class="Greyhound__wrapper">
    <div
      ref="table"
      class="GreyhoundTable"
      :class="{'GreyhoundTable--isLoading': isLoading,'GreyhoundTable--sortAsc': sortOrder === 'asc'}"
    >
      <div class="GreyhoundTableHeader">
        <div
          :class="{ 'GreyhoundTableHeader__date--sortBy' : sortBy === 'date'}"
          @click="sort('date')"
          class="GreyhoundTableHeader__date"
        >Date</div>
        <div
          :class="{ 'GreyhoundTableHeader__track--sortBy' : sortBy === 'track'}"
          @click="sort('track')"
          class="GreyhoundTableHeader__track"
        >Track</div>
        <div
          :class="{ 'GreyhoundTableHeader__trap--sortBy' : sortBy === 'trap'}"
          @click="sort('trap')"
          class="GreyhoundTableHeader__trap"
        >Trap</div>
        <div
          :class="{ 'GreyhoundTableHeader__stmhcp--sortBy' : sortBy === 'sec_time'}"
          @click="sort('sec_time')"
          class="GreyhoundTableHeader__stmhcp"
        >STmHcp</div>
        <div
          :class="{ 'GreyhoundTableHeader__pos--sortBy' : sortBy === 'position'}"
          @click="sort('position')"
          class="GreyhoundTableHeader__pos"
        >Pos</div>
        <div
          :class="{ 'GreyhoundTableHeader__by--sortBy' : sortBy === 'btn_distance'}"
          @click="sort('btn_distance')"
          class="GreyhoundTableHeader__by"
        >By</div>
        <div class="GreyhoundTableHeader__first">1st or 2nd</div>
        <div
          :class="{ 'GreyhoundTableHeader__by--sortBy' : sortBy === 'comment '}"
          @click="sort('comment ')"
          class="GreyhoundTableHeader__remarks"
        >Remarks</div>
        <div
          :class="{ 'GreyhoundTableHeader__winTime--sortBy' : sortBy === 'win_time'}"
          @click="sort('win_time')"
          class="GreyhoundTableHeader__winTime"
        >WinTime</div>
        <div class="GreyhoundTableHeader__weight">Weight</div>
        <div class="GreyhoundTableHeader__sp">SP</div>
        <div
          :class="{ 'GreyhoundTableHeader__calcTm--sortBy' : sortBy === 'adj_time'}"
          @click="sort('adj_time')"
          class="GreyhoundTableHeader__calcTm"
        >CalcTm</div>
      </div>
      <spinner :isActive="isLoading" />
      <greyhound-row :key="`row-${index}`" v-for="(row,index) in rows" :row="row" />
    </div>
  </div>
</template>
