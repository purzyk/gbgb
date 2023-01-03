<script>
import Vue from "vue";
import OpenRacesApi from "../../services/OpenRacesApi";
import Utils from "../../utils/index";

export default {
  props: ["filteroptions"],
  mounted() {
    Utils.inView(this.$el, "TransitionFadeIn");
    this.update();
    this.scroll(this.rows);
  },

  data() {
    return {
      isLoading: true,
      rows: [],
      page: 1,
      itemsPerPage: 5,
      pageCount: 0,
      count: 0,
      filters: [],
      sortBy: false,
      sortOrder: "desc",
      loadedAll: false,
      isActiveToggler: false,
      goToResultsUrl:
        window.__GBGB_GLOBAL__.pages.results + "?raceClass=OR&raceType=race",
      downloadIcon: window.__GBGB_GLOBAL__.icons.download,
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle,
      crossIcon: window.__GBGB_GLOBAL__.icons.crossBig
    };
  },
  methods: {
    scroll(rows) {
      window.onscroll = () => {
        if (this.isLoading) {
          return;
        }
        var el = this.$refs.loadMo;
        var bounding = el.getBoundingClientRect();
        if (
          bounding.top >= 0 &&
          bounding.bottom <=
            (window.innerHeight || document.documentElement.clientHeight)
        ) {
          this.page += 1;
          this.update();
        }
      };
    },
    update() {
      if (this.loadedAll) {
        return;
      }
      this.isLoading = true;
      this.getResults();
    },
    openFilters: function() {
      this.isActiveToggler = !this.isActiveToggler;
      $(".OpenRacesFilters").toggleClass("open");
    },

    getResults() {
      let filters = this.filters.slice();
      OpenRacesApi.getOpenRaces(this.page, this.itemsPerPage, filters).then(
        response => {
          this.rows = this.rows.concat(response.data);
          this.count = response.total;
          this.pageCount = response.last_page;
          this.isLoading = false;
          if (this.page === response.last_page) {
            this.loadedAll = true;
          }
        }
      );
    },
    updateFilter(e) {
      this.loadedAll = false;
      this.page = 1;
      this.pageCount = 0;
      this.rows = [];
      this.filters = e;
      this.update();
    },
    loadMore() {
      this.page += 1;
      this.update();
    },
    handleScroll() {
      if (this.isLoading || this.loadedAll) {
        return;
      }
    },
    print() {
      window.print();
    }
  }
};
</script>

<template>
  <section class="OpenRaces">
    <div class="OpenRaces__inner">
      <div class="OpenRaces__inner__header">
        <div class="OpenRaces__wrap">
          <p
            class="OpenRaces__text"
          >Filter the results by entering the dates or types of races you want to see.</p>
          <a
            type="button"
            class="OpenRaces__goToResults Button Button--primary"
            :href="goToResultsUrl"
          >
            <span class="Meta">Open races</span>
          </a>
          <button type="button" class="OpenRaces__print Button Button--primary" @click="print">
            <span v-html="downloadIcon" class="Icon SvgContainer" aria-hidden="true"></span>
            <span class="Meta">Download calendar</span>
          </button>
        </div>
        <div
          class="OpenRacesFilters__wrapper"
          v-bind:class="{ 'OpenRacesFilters__wrapper--active': isActiveToggler }"
        >
          <button type="button" class="OpenRacesFilters__toggler Button" v-on:click="openFilters">
            <span class="Meta">Filter</span>
            <span
              v-if="!isActiveToggler"
              v-html="triangleIcon"
              class="Icon__triangle Icon SvgContainer"
              aria-hidden="true"
            ></span>
            <span
              v-else
              v-html="crossIcon"
              class="Icon__cross Icon SvgContainer"
              aria-hidden="true"
            ></span>
          </button>
          <open-races-filters
            @filter="updateFilter"
            :filterOptions="filteroptions"
            ref="openFilters"
          />
        </div>
      </div>

      <open-races-table :rows="rows" :isLoading="isLoading"/>
      <div class="OpenRaces__loadMore" ref="loadMo"></div>
    </div>
  </section>
</template>
