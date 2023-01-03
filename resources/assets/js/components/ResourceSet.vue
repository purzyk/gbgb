<script>
import Vue from "vue";
import $ from "jquery";

export default {
  props: ["title"],
  data() {
    return {
      isExtended: false,
      starIcon: window.__GBGB_GLOBAL__.icons.star,
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron
    };
  },
  methods: {
    toggle() {
      if (this.isExtended) {
        $(this.$refs.inner).slideUp();
      } else {
        $(this.$refs.inner).slideDown({
          start: function() {
            $(this).css({
              display: "flex"
            });
          }
        });
      }
      this.isExtended = !this.isExtended;
      this.$emit("toggled", { isExtended: this.isExtended, component: this });
    },
    hide() {
      $(this.$refs.inner).slideUp();
      this.isExtended = false;
    }
  }
};
</script>

<template>
  <div class="ResourceSet" :class="{'ResourceSet--isExtended':isExtended}">
    <div class="ResourceSet__title_bar" @click="toggle">
      <h3 class="ResourceSet__title">{{ title }}</h3>
      <button type="button" aria-label="Expand/toggle item" class="ResourceSet__button Button">
        <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </button>
    </div>
    <div class="ResourceSet__inner" ref="inner">
      <slot></slot>
    </div>
  </div>
</template>
