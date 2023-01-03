<script>
import Vue from "vue";
import Utils from "../utils/index";

export default {
  props: ["post"],
  mounted() {
    Utils.inView(this.$el, "TransitionFadeIn");
  },
  data() {
    return {
      instagramIcon: window.__GBGB_GLOBAL__.icons.instagram,
      twitterIcon: window.__GBGB_GLOBAL__.icons.twitter
    };
  }
};
</script>

<template>
  <masonry-item
    class="SocialMediaPost"
    :class="['SocialMediaPost--' + post.acf.socialMediaNetwork]"
  >
    <div class="SocialMediaPost__image">
      <img :src="post.thumbnail" alt v-if="post.thumbnail">
    </div>
    <div class="SocialMediaPost__inner">
      <div class="SocialMediaPost__nicename">{{ post.acf.socialMediaUserNicename }}</div>
      <a
        :href="post.acf.socialMediaSource"
        class="SocialMediaPost__username"
      >{{ post.acf.socialMediaUserName }}</a>
      <div class="SocialMediaPost__content" v-html="post.post_content"></div>
      <div class="SocialMediaPost__bottom">
        <time class="SocialMediaPost__bottom__date">{{ post.acf.socialMediaDate }}</time>
        <a
          :href="post.acf.socialMediaSource"
          class="SocialMediaPost__bottom__link"
          target="_blank"
          rel="noopener noreferrer"
        >
          <span
            v-if="post.acf.socialMediaNetwork == 'instagram'"
            v-html="instagramIcon"
            class="Icon Icon--instagram SvgContainer"
            aria-hidden="true"
          ></span>
          <span
            v-if="post.acf.socialMediaNetwork == 'twitter'"
            v-html="twitterIcon"
            class="Icon Icon--twitter SvgContainer"
            aria-hidden="true"
          ></span>
        </a>
      </div>
    </div>
  </masonry-item>
</template>
