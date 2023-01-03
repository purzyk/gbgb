<script>
import Vue from "vue";
import ApiService from "../../services/ApiService";
import Utils from "../../utils/index";

export default {
  props: ["name"],
  mounted() {
    Utils.inView(this.$el, "TransitionFadeIn");
    this.update();
  },
  data() {
    return {
      isLoading: true,
      rows: [],
      page: 1,
      itemsPerPage: 20,
      pageCount: 0,
      count: 0,
      filters: [],
      sortBy: false,
      sortOrder: "desc"
    };
  },
  methods: {
    update() {
      this.isLoading = true;
      this.getTrainers();
    },
    getTrainers() {
      let filters = this.filters.slice();
      if (this.sortBy) {
        filters.push({ name: "sort_by", value: this.sortBy });
        filters.push({ name: "sort_dir", value: this.sortOrder });
      }
      ApiService.getTrainers(this.page, this.itemsPerPage, filters).then(
        response => {
          this.rows = response.items;
          this.count = response.meta.count;
          this.pageCount = response.meta.pageCount;
          this.isLoading = false;
        }
      );
    },
    updateFilter(e) {
      this.page = 1;
      this.filters = e;
      this.update();
    },
    updatePagination(e) {
      this.page = e.page;
      this.itemsPerPage = e.itemsPerPage;
      this.update();
    },
    updateSorting(e) {
      this.page = 1;
      this.sortBy = e.sortBy;
      this.sortOrder = e.sortOrder;
      this.update();
    }
  }
};
</script>

<template>
  <section class="TrainersList">
    <h2 class="TrainersList__title">{{ name }}</h2>
    <div class="TrainersList__inner OverlapsHero">
      <trainers-list-filter @filter="updateFilter" ref="search"/>
      <trainers-list-table :rows="rows" :isLoading="isLoading" @sort="updateSorting"/>
      <live-results-pagination
        :page="page"
        :itemsPerPage="itemsPerPage"
        :count="count"
        :pageCount="pageCount"
        :isLoading="isLoading"
        @pagination="updatePagination"
      />
    </div>
  </section>
</template>
