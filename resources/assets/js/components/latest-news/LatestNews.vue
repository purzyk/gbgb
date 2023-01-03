<script>
import Vue from "vue";
import ApiService from "../../services/ApiService";
import $ from "jquery";

export default {
  props: [
    "categories",
    "contenttypes",
    "startingyear",
    "defaultcategory"
  ],
  mounted() {
    if (this.defaultcategory) {
      const defaultCategoryObject = this.categories.find(cat => {
        return cat.value === parseInt(this.defaultcategory, 10);
      });
      if (defaultCategoryObject) {
        this.$refs.filters.loadDefaultCategory(defaultCategoryObject);
      }
    }
    this.getPosts();
    $(window).on("scroll", this.handleScroll);
  },
  data() {
    return {
      posts: [],
      isLoading: false,
      currentPage: 1,
      loadedAll: false,
      filters: {},
      timeout: null
    };
  },
  computed: {
    displayedPosts() {
      return this.posts;
    }
  },
  methods: {
    handleScroll() {
      if (this.isLoading || this.loadedAll) {
        return;
      }
      const trigger = $(".LatestNews__loadMore").offset().top;
      if ($(window).scrollTop() + $(window).height() > trigger) {
        this.getPosts();
      }
    },
    handleFilters(e) {
      this.filters = e;
      //only get posts 500ms after last filter change
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        this.currentPage = 1;
        this.loadedAll = false;
        this.posts = [];
        this.getPosts();
      }, 500);
    },
    getPosts() {
      let args = {
        per_page: 6,
        page: this.currentPage
      };
      args = Object.assign(args, this.filters);
      this.isLoading = true;
      ApiService.getPosts(args)
        .then(response => {
          this.isLoading = false;
          response.data.forEach(post => {
            this.posts.push(post);
          });
          if (this.currentPage >= response.headers["x-wp-totalpages"]) {
            this.loadedAll = true;
          }
          this.currentPage++;
        })
        .catch(() => {
          this.isLoading = false;
          this.loadedAll = true;
        });
    },
    getCategoryName(categoryId) {
      let categoryName = "";
      this.categories.forEach(category => {
        if (categoryId === category.value) {
          categoryName = category.label;
        }
      });
      return categoryName;
    }
  }
};
</script>

<template>
  <section class="LatestNews">
    <latest-news-filters
      @change="handleFilters"
      :categories="categories"
      :contentTypes="contenttypes"
      :startingyear="startingyear"
      ref="filters"
    />
    <div class="LatestNews__grid">
      <latest-news-item
        v-for="(post, index) in displayedPosts"
        :key="`post-${index}`"
        :link="post.link"
        :image="post.featuredImageSrc.normal"
        :imageRetina="post.featuredImageSrc.retina"
        :categories="getCategoryName(post.categories[0])"
        :date="post.formattedDate"
        :title="post.title.rendered"
        :excerpt="post.excerpt.rendered"
      />
    </div>
    <div class="LatestNews__loadMore"></div>
    <spinner :isActive="isLoading"/>
  </section>
</template>
