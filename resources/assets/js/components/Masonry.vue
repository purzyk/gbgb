<script>
import Vue from "vue";
import Isotope from "isotope-layout";
import $ from "jquery";

export default {
  created() {
    this.refresh();
  },
  updated() {
    this.refresh();
    $("." + this.uClass + " .Masonry__item img").on(
      "load",
      this.refresh.bind(this)
    );
  },
  props: { horizontalOrder: Boolean },
  data() {
    return {
      uClass: "Masonry-" + this._uid,
      isExtended: false,
      isReady: false
    };
  },
  methods: {
    setMasonry() {
      this.masonry = new Isotope(`.${this.uClass} .Masonry__inner`, {
        itemSelector: `.${this.uClass}  .Masonry__item`,
        percentPosition: true,
        transitionDuration: 0,
        masonry: {
          columnWidth: `.${this.uClass}  .Masonry__sizer`,
          gutter: `.${this.uClass}  .Masonry__gutter_sizer`,
          horizontalOrder: this.horizontalOrder
        }
      });
    },
    refresh() {
      // doesn't work with nextTick
      setTimeout(() => {
        if (!this.masonry) {
          this.setMasonry();
        } else {
          this.masonry.destroy();
          this.setMasonry();
        }
        this.isReady = true;
      }, 50);
    },
    extend(event) {
      document.documentElement.scrollTop = event.pageY - 50;
      this.isExtended = true;
      this.refresh();
    }
  }
};
</script>

<template>
  <div
    class="Masonry"
    :class="[{ 'Masonry--isExtended': isExtended, 'Masonry--isReady' : isReady }, uClass ]"
  >
    <div class="Masonry__inner">
      <div class="Masonry__sizer"></div>
      <div class="Masonry__gutter_sizer"></div>
      <slot></slot>
    </div>
    <button
      type="button"
      v-on:click="extend($event)"
      class="Masonry__button Button Button--white__outline"
    >See More</button>
  </div>
</template>
