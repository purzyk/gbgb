<script>
import Vue from "vue";

export default {
  props: ["post"]
};
</script>

<template>
  <masonry-item class="OurDogsPost">
    <div class="OurDogsPost__image">
      <img :src="post.thumbnail" alt>
    </div>
    <div class="OurDogsPost__inner">
      <div class="OurDogsPost__caption">
        <span v-if="post.race_name">Race name:</span>
        {{ post.race_name }}
      </div>
      <div class="OurDogsPost__author">
        <span v-if="post.pet_name">Pet name:</span>
        {{ post.pet_name }}
      </div>
    </div>
  </masonry-item>
</template>
