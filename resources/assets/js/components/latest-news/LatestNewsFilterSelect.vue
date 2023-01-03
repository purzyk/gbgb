<script>
import Vue from "vue";
import $ from "jquery";

export default {
  props: ["options", "type", "title"],
  mounted() {
    $(document).mouseup(e => {
      const container = $(".LatestNewsFilterSelect");
      if (!container.is(e.target) && container.has(e.target).length === 0) {
        this.isExtended = false;
      }
    });
  },
  data() {
    return {
      isExtended: false,
      activeFilters: [],
      filterObjects: this.options.map(element => {
        element["type"] = this.type;
        return element;
      }),
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle
    };
  },
  methods: {
    removeFilter(filterObject) {
      const index = this.getActiveFilterIndex(filterObject);
      this.activeFilters.splice(index, 1);
      this.handleChange();
    },
    removeAllFilters() {
      this.activeFilters = [];
      this.handleChange();
    },
    addFilter(filterObject) {
      this.activeFilters.push(filterObject);
      this.handleChange();
    },
    getActiveFilterIndex(filterObject) {
      let activeFilterIndex = -1;
      this.activeFilters.forEach((element, index) => {
        if (filterObject.value === element.value) {
          activeFilterIndex = index;
        }
      });
      return activeFilterIndex;
    },
    switchFilter(filterObject) {
      if (this.getActiveFilterIndex(filterObject) > -1) {
        this.removeFilter(filterObject);
      } else {
        this.addFilter(filterObject);
      }
    },
    handleChange() {
      this.$emit("change", { type: this.type, filters: this.activeFilters });
    },
    switchExtended() {
      this.isExtended = !this.isExtended;
      this.$emit("switchExtended", {
        type: this.type,
        isExtended: this.isExtended
      });
    },
    setExtended(value) {
      this.isExtended = value;
    }
  }
};
</script>

<template>
  <div class="LatestNewsFilterSelect" :class="{ 'LatestNewsFilterSelect--isExtended' : isExtended}">
    <div class="LatestNewsFilterSelect__button Button" @click="switchExtended">
      <span class="Meta">{{ title }}</span>
      <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
    <div class="LatestNewsFilterSelect__extended">
      <div
        class="LatestNewsFilterSelect__option"
        :class="{ 'LatestNewsFilterSelect__option--active' : getActiveFilterIndex(filter) > -1}"
        v-for="(filter,index) in filterObjects"
        :key="`filter-${index}`"
        @click="switchFilter(filter)"
      >
        <div class="LatestNewsFilterSelect__checkbox">&check;</div>
        {{ filter.label }}
      </div>
    </div>
  </div>
</template>
