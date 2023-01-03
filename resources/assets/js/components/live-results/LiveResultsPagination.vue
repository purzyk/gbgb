<script>
import Vue from "vue";

export default {
  props: ["page", "itemsPerPage", "count", "pageCount", "isLoading"],
  data() {
    return {
      tableScrollLeft: "0px",
      ellipsisLeft: false,
      ellipsisRight: false,
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron
    };
  },
  methods: {
    changeItemsPerPage(itemsPerPage) {
      this.page = 1;
      this.$emit("pagination", {
        page: this.page,
        itemsPerPage: itemsPerPage
      });
    },
    changePage(page) {
      if (page > 0 && page <= this.pageCount)
        this.$emit("pagination", {
          page: page,
          itemsPerPage: this.itemsPerPage
        });
    },
    handleEllipsisLeftClick() {
      let targetPage = this.page - 10;
      if (targetPage < 1) {
        targetPage = 1;
      }
      this.changePage(targetPage);
    },
    handleEllipsisRightClick() {
      let targetPage = this.page + 10;
      if (targetPage > this.pageCount) {
        targetPage = this.pageCount;
      }
      this.changePage(targetPage);
    }
  },
  computed: {
    pageLinks() {
      let startingPage = this.page - 4;
      if (startingPage > this.pageCount - 9) {
        startingPage = this.pageCount - 9;
      }
      if (startingPage < 2) {
        startingPage = 2;
      }
      let links = [];
      for (let i = 0; i < 9; i++) {
        const page = startingPage + i;
        if (page < this.pageCount) {
          links.push(page);
        }
      }
      this.ellipsisLeft = links[0] > 2;
      this.ellipsisRight = links[links.length - 1] < this.pageCount - 1;
      return links;
    }
  }
};
</script>
<template>
  <div class="LiveResultsPagination">
    <div class="LiveResultsPagination__pages">
      <a
        class="LiveResultsPagination__page LiveResultsPagination__page--prev"
        @click="changePage(page-1)"
      >
        <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </a>
      <a
        class="LiveResultsPagination__page"
        :class="{'LiveResultsPagination__page--active' : page === 1}"
        @click="changePage(1)"
      >1</a>
      <a
        class="LiveResultsPagination__ellipsis"
        v-if="this.ellipsisLeft"
        @click="handleEllipsisLeftClick"
      >&hellip;</a>
      <a
        class="LiveResultsPagination__page"
        :class="{'LiveResultsPagination__page--active' : page === index}"
        :key="index"
        v-for="index in pageLinks"
        @click="changePage(index)"
      >{{index}}</a>
      <a
        class="LiveResultsPagination__ellipsis"
        v-if="this.ellipsisRight"
        @click="handleEllipsisRightClick"
      >&hellip;</a>
      <a
        class="LiveResultsPagination__page"
        :class="{'LiveResultsPagination__page--active' : page === pageCount}"
        v-if="pageCount>1"
        @click="changePage(pageCount)"
      >{{pageCount}}</a>
      <a
        class="LiveResultsPagination__page LiveResultsPagination__page--next"
        @click="changePage(page+1)"
      >
        <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </a>
    </div>
    <div class="LiveResultsPagination__pageSizes">
      Page size
      <div
        class="LiveResultsPagination__pageSize"
        :class="{'LiveResultsPagination__pageSize--active' : itemsPerPage === 20}"
        @click="changeItemsPerPage(20)"
      >20</div>
      <div
        class="LiveResultsPagination__pageSize"
        :class="{'LiveResultsPagination__pageSize--active' : itemsPerPage === 50}"
        @click="changeItemsPerPage(50)"
      >50</div>
      <div
        class="LiveResultsPagination__pageSize"
        :class="{'LiveResultsPagination__pageSize--active' : itemsPerPage === 100}"
        @click="changeItemsPerPage(100)"
      >100</div>
    </div>
    <div class="LiveResultsPagination__count">{{count}} items in {{pageCount}} pages</div>
  </div>
</template>
