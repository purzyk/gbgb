<script>
import Vue from "vue";
import ApiService from "../services/ApiService";

export default {
  created() {
    this.getFeed();
  },
  props: ["hashtag", "feed", "ids"],
  data() {
    return {
      posts: {},
      hasPosts: false
    };
  },
  methods: {
    getFeed() {
      ApiService.getSocialMediaFeed(this.ids, this.feed).then(response => {
        this.posts = response;
        this.hasPosts = true;
      });
    }
  }
};
</script>

<template>
  <section class="SocialMediaFeed" :class="{ 'SocialMediaFeed--hasPosts' : hasPosts}">
    <div class="SocialMediaFeed__header">
      <p class="SocialMediaFeed__header__title">Social Feed</p>
      <h2 class="SocialMediaFeed__header__hashtag">{{ hashtag }}</h2>
    </div>
    <div class="SocialMediaFeed__section">
      <div class="SocialMediaFeed__section_inner">
        <masonry class="SocialMediaFeed__posts">
          <social-media-post v-for="(post) in posts" v-bind:key="post.id" v-bind:post="post"></social-media-post>
        </masonry>
      </div>
      <spinner :isActive="!hasPosts"/>
    </div>
  </section>
</template>
