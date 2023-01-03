<script>
import Vue from "vue";
import Utils from "../../utils/index";

export default {
  props: [
    "link",
    "image",
    "imageRetina",
    "categories",
    "date",
    "title",
    "excerpt"
  ],
  mounted() {
    Utils.inView(this.$el, "TransitionFadeIn");
  },
  computed: {
    srcset() {
      return this.imageRetina + " 2x";
    }
  }
};
</script>

<template>
  <a :href="link" class="LatestNewsItem">
    <div class="LatestNewsItem__image">
      <img v-if="image" :src="image" :srcset="srcset" alt>
    </div>
    <div class="LatestNewsItem__inner">
      <div class="LatestNewsItem__category">
        {{ categories }}
        <span class="LatestNewsItem__date">// {{ date }}</span>
      </div>
      <h3 class="LatestNewsItem__title">{{ title }}</h3>
      <div class="LatestNewsItem__excerpt" v-html="excerpt"></div>
    </div>
  </a>
</template>
