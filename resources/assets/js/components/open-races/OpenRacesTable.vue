<script>
import Vue from "vue";
var moment = require("moment");

export default {
  props: ["isLoading", "rows"],
  data() {
    return {};
  },
  methods: {
    isRepeatedDate(index) {
      if (index === 0) {
        return false;
      }
      const date = this.rows[index].RaceStageDate;
      const previousDate = this.rows[index - 1].RaceStageDate;
      return date === previousDate;
    }
  }
};
</script>

<template>
  <div class="OpenRacesTable" :class="{'OpenRacesTable--isLoading': isLoading}">
    <open-races-row
      :key="`row-${index}`"
      v-for="(row,index) in rows"
      :row="row"
      :isRepeatedDate="isRepeatedDate(index)"
    ></open-races-row>
    <spinner :isActive="isLoading"/>
  </div>
</template>
