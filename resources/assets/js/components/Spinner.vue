<script>
import Vue from "vue";

export default {
  props: ["isActive"],
  data() {
    return {
      spinnerIcon: window.__GBGB_GLOBAL__.icons.spinner
    }
  }
};
</script>

<template>
  <div v-if="isActive" class="Spinner">
    <span v-html="spinnerIcon" class="Icon SvgContainer" aria-hidden="true"></span>
  </div>
</template>
