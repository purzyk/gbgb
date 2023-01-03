<script>
import Vue from "vue";

export default {
  props: ["pagetitle", "disableBack"],
  data() {
    return {
      myKennelUrl: window.__GBGB_GLOBAL__.pages.myKennel,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow
    };
  }
};
</script>

<template>
  <div class="MyKennel__header__title">
    <a
      :href="myKennelUrl"
      aria-label="Back to previous page"
      class="MyKennel__header__title__back"
      v-if="!disableBack"
    >
      <span v-html="arrowIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </a>
    <h1 class="MyKennel__header__title__meta">{{ pagetitle }}</h1>
  </div>
</template>
