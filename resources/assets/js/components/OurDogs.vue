<script>
import Vue from "vue";
import ApiService from "../services/ApiService";

export default {
  props: ["pageid"],
  created() {
    this.getFeed();
    $(window).on("resize", this.forceContainerHeight.bind(this));
  },
  data() {
    return {
      posts: [],
      page: 1,
      perPage: 10,
      isLoading: true,
      loadedAll: false,
      scrollTo: false
    };
  },
  methods: {
    getFeed() {
      this.isLoading = true;
      ApiService.getOurDogs(this.pageid, this.page, this.perPage).then(
        response => {
          this.posts = this.posts.concat(response.data);
          if (this.page >= response.headers["x-wp-totalpages"]) {
            this.loadedAll = true;
          }
          this.page += 1;
          this.isLoading = false;
          this.forceContainerHeight();
        }
      );
    },
    forceContainerHeight() {
      //prevent layout "jumping" during masonry reflow
      setTimeout(() => {
        this.$refs.inner.style.minHeight = this.$refs.inner.offsetHeight + "px";
      }, 500);
    },
    loadMore(e) {
      if (!this.isLoading) {
        this.getFeed();
      }
    }
  }
};
</script>

<template>
  <section class="OurDogs">
    <h2 class="OurDogs__title">MY KENNEL FEED</h2>
    <h3 class="OurDogs__hashtag">#OurGreyhounds</h3>
    <div class="OurDogs__section">
      <div class="OurDogs__section_inner" ref="inner">
        <masonry class="OurDogs__posts">
          <our-dogs-post v-for="(post) in posts" v-bind:key="post.id" v-bind:post="post"></our-dogs-post>
        </masonry>
        <spinner :isActive="isLoading" />
        <div @click="loadMore" class="OurDogs__seeMore" v-if="!loadedAll&&!isLoading">See more</div>
      </div>
    </div>
  </section>
</template>
