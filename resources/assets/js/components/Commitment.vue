<script>
import Vue from "vue";
import $ from "jquery";
import Utils from "../utils/index";

export default {
  props: ["tiles", "tilesbackground"],
  data() {
    return {
      expanded: false,
      activeContainer: false,
      showContent: -1,
      tileHeight: "443px",
      crossIcon: window.__GBGB_GLOBAL__.icons.cross,
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevronBig,
      plusIcon: window.__GBGB_GLOBAL__.icons.plus
    };
  },
  mounted() {
    this.setTileHeight();
    $(window).on("resize", this.setTileHeight.bind(this));
  },
  methods: {
    setTileHeight() {
      setTimeout(() => {
        this.tileHeight = $(".CommitmentTile").width() + "px";
      }, 300);
    },
    setActiveContainer() {
      this.activeContainer = !this.activeContainer;
    },
    toggle(index, event) {
      this.expanded = index;
      this.showContent === index
        ? (this.showContent = -1)
        : (this.showContent = index);
      this.setActiveContainer();
      if ($(document).width() < 600 && this.showContent !== -1) {
        setTimeout(() => {
          const $tile = $(this.$refs.tile[index]);
          $(window).scrollTop(
            $(".CommitmentTile__content", $tile).offset().top
          );
        }, 100);
      }
    }
  },
  computed: {
    counter() {
      if (this.expanded !== false) {
        const expandedNumber = this.expanded + 1;
        return "0" + +expandedNumber + " / 0" + this.tiles.length;
      } else {
        return "";
      }
    }
  }
};
</script>

<template>
  <div class="Commitment">
    <div class="Commitment__inner" :class="{ 'Commitment__inner--expanded' : activeContainer }">
      <img :src="tilesbackground" class="Commitment__background" />
      <div class="Commitment__tiles">
        <div
          class="CommitmentTile"
          v-for="(tile, index) in tiles"
          :key="`item-${index}`"
          @click="toggle(index,$event)"
          :style="{height: tileHeight}"
          ref="tile"
        >
          <p class="CommitmentTile__number">0{{index + 1}}</p>
          <div class="CommitmentTile__description">{{tile.commitmentTilesTileDescription}}</div>
          <button
            v-show="index !== showContent"
            type="button"
            class="CommitmentTile__button--open CommitmentTile__button Button"
          >
            <span v-html="plusIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </button>
          <button
            v-show="index === showContent"
            type="button"
            class="CommitmentTile__button--close CommitmentTile__button Button"
          >
            <span v-html="plusIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </button>
          <div
            v-show="index === showContent"
            v-html="tile.commitmentTilesDescription"
            class="CommitmentTile__content"
          ></div>
        </div>
      </div>
      <div class="CommitmentExpanded" v-if="expanded >= 0">
        <div class="CommitmentExpanded__inner">
          <div class="CommitmentExpanded__controls">
            <button
              type="button"
              aria-label="Close commitment"
              class="CommitmentExpanded__close Button"
              @click="activeContainer = false; expanded=-1;showContent=-1; setTileHeight();"
            >
              <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
            </button>
            <p class="CommitmentExpanded__counter">{{ counter }}</p>
            <button
              type="button"
              aria-label="Previous commitment"
              class="CommitmentExpanded__prev Button"
              @click="expanded -= 1"
              v-if="expanded > 0"
            >
              <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
            </button>
            <button
              type="button"
              aria-label="Next commitment"
              class="CommitmentExpanded__next Button"
              @click="expanded += 1"
              v-if="expanded < tiles.length - 1"
            >
              <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
            </button>
          </div>
          <div
            v-if="expanded !== false"
            class="CommitmentExpanded__title"
          >{{ tiles[expanded].commitmentTilesTitle }}</div>
          <div
            class="CommitmentExpanded__description"
            v-if="expanded !== false"
            v-html="tiles[expanded].commitmentTilesDescription"
          ></div>
        </div>
      </div>
    </div>
  </div>
</template>
