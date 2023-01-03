<script>
import Vue from "vue";
import ResultsApi from "../../services/ResultsApi";
import Utils from "../../utils/index";

export default {
  props: [
    "full",
    "raceclass",
    "trackname",
    "greyhoundname",
    "greyhoundid",
    "racedate",
    "racetype",
    "enablesorting"
  ],
  mounted() {
    Utils.inView(this.$el, "TransitionFadeIn");
    const greyhoundFilter = this.greyhoundname && this.greyhoundid;
    const hasFilters =
      this.trackname || greyhoundFilter || this.racedate || this.racetype;
    if (hasFilters) {
      this.$refs.search.loadFilter({
        trackName: this.trackname,
        greyhoundName: this.greyhoundname,
        greyhoundId: this.greyhoundid,
        raceDate: this.racedate,
        raceType: this.racetype,
        raceClass: this.raceclass
      });
    } else {
      this.update();
    }
    if (!this.full) {
      setInterval(() => {
        this.updateAndMerge();
      }, 60000);
    }
  },
  data() {
    return {
      isLoading: true,
      rows: [],
      page: 1,
      itemsPerPage: this.full ? 20 : 5,
      pageCount: 0,
      count: 0,
      filters: [],
      pageurl: window.__GBGB_GLOBAL__.pages.results,
      openracesurl: window.__GBGB_GLOBAL__.pages.openraces,
      sortBy: false,
      sortOrder: "desc"
    };
  },
  methods: {
    update() {
      this.isLoading = true;
      this.getResults();
    },
    updateAndMerge() {
      this.isLoading = true;
      this.getResults(true);
    },
    getResults(merge = false) {
      let filters = this.filters.slice();
      if (this.sortBy) {
        filters.push({ name: "sort_by", value: this.sortBy });
        filters.push({ name: "sort_dir", value: this.sortOrder });
      }
      ResultsApi.getLatestResults(this.page, this.itemsPerPage, filters).then(
        response => {
          if (merge) {
            const itemsToAdd = response.items.filter(race => {
              let result = true;
              this.rows.forEach(row => {
                if (row.raceId === race.raceId) {
                  result = false;
                }
              });
              return result;
            });
            if (itemsToAdd) {
              this.rows = itemsToAdd.concat(this.rows);
            }
          } else {
            this.rows = response.items;
          }
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
  <section class="LiveResults" :class="{'LiveResults--full': full}">
    <h2 v-if="!full" class="LiveResults__title">Latest Results</h2>
    <div class="LiveResults__inner OverlapsHero">
      <live-results-search
        :pageUrl="pageurl"
        :goToPage="!full"
        @filter="updateFilter"
        ref="search"
      />
      <live-results-table
        :rows="rows"
        :full="full"
        :isLoading="isLoading"
        :viewDetailsButton="true"
        :enablesorting="enablesorting"
        @sort="updateSorting"
      />
      <live-results-pagination
        v-if="full"
        :page="page"
        :itemsPerPage="itemsPerPage"
        :count="count"
        :pageCount="pageCount"
        :isLoading="isLoading"
        @pagination="updatePagination"
      />
      <div v-if="!full" class="LiveResults__footer">
        <a :href="openracesurl" class="LiveResults__view_open">View Open Races</a>
        <a
          :href="pageurl"
          class="LiveResults__see__all Button Button--primary__outline"
        >See all results</a>
      </div>
    </div>
  </section>
</template>
