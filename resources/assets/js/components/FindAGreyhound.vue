<script>
import Vue from "vue";
import ResultsApi from "../services/ResultsApi";

export default {
  props: ["cta", "mode"],
  data() {
    return {
      greyhoundId: false,
      greyhoundPageUrl: window.__GBGB_GLOBAL__.pages.greyhound,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow
    };
  },
  methods: {
    handleGreyhoundChange(e) {
      this.greyhoundId = e.greyhoundId;
    },
    handleGo() {
      if (this.greyhoundId && this.mode === "search") {
        let url = this.greyhoundPageUrl + "?greyhoundId=" + this.greyhoundId;
        window.location.href = url;
      }
      if (this.greyhoundId && this.mode === "add") {
        this.$emit("addGreyhound", { dogId: this.greyhoundId });
      }
    }
  }
};
</script>

<template>
  <div class="FindAGreyhound">
    <greyhound-input
      @change="handleGreyhoundChange"
      ref="greyhoundInput"
      class="LiveResultsSearch__name"
      :placeholder="cta"
    ></greyhound-input>
    <button
      type="button"
      aria-label="Search"
      class="FindAGreyhound__go Button Button--primary"
      :class="{'FindAGreyhound__go--active' : this.greyhoundId}"
      @click="handleGo"
    >GO</button>
  </div>
</template>
