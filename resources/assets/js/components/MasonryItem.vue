<script>
import Vue from "vue";

export default {
  props: {
    size: {
      default: "single",
      type: String
    }
  }
};
</script>

<template>
  <div class="Masonry__item" :class="`Masonry__item--${this.size}`">
    <slot></slot>
  </div>
</template>
