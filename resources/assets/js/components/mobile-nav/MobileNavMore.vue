<script>
import Vue from "vue";
import $ from "jquery";

export default {
  props: ["items"],
  data() {
    return {
      plusIcon: window.__GBGB_GLOBAL__.icons.plus,
      minusIcon: window.__GBGB_GLOBAL__.icons.minus
    };
  },
  methods: {
    handleClick(hasChildren, event) {
      const $target = $(event.target);
      if (hasChildren) {
        $target
          .closest(".MobileNavMore__item")
          .toggleClass("MobileNavMore__item--isExtended");
      } else {
        window.location.href = $target.attr("href");
      }
    }
  }
};
</script>

<template>
  <div class="MobileNavMore">
    <div class="MobileNavMore__item" v-for="(item, index) in items" :key="`item-${index}`">
      <a
        class="MobileNavMore__link"
        @click.prevent="handleClick(item.hasChildren, $event)"
        :href="item.link"
      >
        <span v-html="plusIcon" class="Icon--plus Icon SvgContainer" aria-hidden="true"></span>
        <span v-html="minusIcon" class="Icon--minus Icon SvgContainer" aria-hidden="true"></span>
        <span class="Meta">{{ item.title }}</span>
      </a>
      <div class="MobileNavMore__children" v-if="item.hasChildren">
        <a
          :href="item.link"
          class="MobileNavMore__child"
          v-for="(child, index) in item.children"
          :key="`item-${index}`"
        >
          <img :src="child.image" alt="">
          {{ child.title }}
        </a>
      </div>
    </div>
  </div>
</template>
