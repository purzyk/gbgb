<script>
import Vue from "vue";
import $ from "jquery";

export default {
  props: ["allSubsections", "currentSection"],
  computed: {
    subsectionToShow() {
      return this.allSubsections[this.currentSection];
    }
  }
};
</script>

<template>
  <div class="MobileNavSubsection">
    <a
      :href="item.link"
      class="MobileNavSubsection__item"
      v-for="(item, index) in subsectionToShow"
      :key="`item-${index}`"
    >
      <img :src="item.image" alt="">
      <span v-html="item.title" class="MobileNavSubsection__item__meta"></span>
    </a>
  </div>
</template>
