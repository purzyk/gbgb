<script>
import Vue from "vue";
import $ from "jquery";

export default {
  props: ["logo", "siteurl"],
  mounted() {
    $(window).on("resize", this.checkMore.bind(this));
  },
  data() {
    return {
      currentSection: null,
      showMore: false,
      itemsInMore: [],
      items: window.__GBGB_GLOBAL__.mobileMenu,
      subsections: window.__GBGB_GLOBAL__.mobileMenuSubsections,
      userIcon: window.__GBGB_GLOBAL__.icons.user,
      searchIcon: window.__GBGB_GLOBAL__.icons.search,
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle,
      myKennelUrl: window.__GBGB_GLOBAL__.pages.myKennel
    };
  },
  computed: {
    moreContent() {
      let content = [];
      this.itemsInMore.forEach(itemLink => {
        let itemToAdd = this.items[itemLink];
        if (this.subsections[itemLink]) {
          itemToAdd["children"] = this.subsections[itemLink];
        }
        content.push(itemToAdd);
      });
      if (content.length === 0) {
        this.showMore = false;
      }
      return content;
    }
  },
  methods: {
    handleTopClick(link) {
      if (link === this.currentSection) {
        this.currentSection = null;
      } else if (link === "#more") {
        this.currentSection = null;
        this.showMore = !this.showMore;
      } else if (this.subsections[link]) {
        this.showMore = false;
        this.currentSection = link;
      } else {
        window.location.href = link;
      }
    },
    checkMore() {
      let totalWidth = 0;
      let maxWidth = $(".MobileMenu__top").innerWidth();
      $(".MobileMenu__item").show();
      this.itemsInMore = [];

      $(".MobileMenu__item").each(function() {
        totalWidth += $(this).outerWidth(true);
      });
      if (totalWidth > maxWidth) {
        this.showMoreLink();
      } else {
        $(".MobileMenu__more").hide();
      }
    },
    showMoreLink() {
      let itemsInMore = this.itemsInMore;
      $(".MobileMenu__more").show();
      let maxWidth =
        $(".MobileMenu__top").innerWidth() -
        $(".MobileMenu__more").outerWidth() -
        20;
      let totalWidth = 0;
      $(".MobileMenu__item").each(function() {
        totalWidth += $(this).outerWidth(true);
        if (totalWidth > maxWidth) {
          $(this).hide();
          itemsInMore.push($(this).attr("href"));
        }
      });
      this.itemsInMore = itemsInMore;
    }
  }
};
</script>

<template>
  <div class="MobileNav" :class="{ 'MobileNav--extended' : currentSection || showMore}">
    <div class="MobileHeader">
      <a :href="siteurl" class="MobileHeader__logo">
        <img :src="logo" alt>
      </a>
      <ul class="MobileHeader__actions">
        <li class="MobileHeader__actions__item">
          <a href="#search" type="button" class="Item__search Item Button">
            <span v-html="searchIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </a>
        </li>
        <li class="MobileHeader__actions__item">
          <a :href="myKennelUrl" class="Item Item__myKennel">
            <span v-html="userIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </a>
        </li>
      </ul>
    </div>
    <div class="MobileMenu">
      <div class="MobileMenu__top">
        <ul class="MobileMenu__top__list">
          <li v-for="(item) in items" :key="item.objectId" class="MobileMenu__top__list__item">
            <a
              :href="item.link"
              class="MobileMenu__item"
              @click.prevent="handleTopClick(item.link)"
              :class="{ 'MobileMenu__item--isExtended' : currentSection === item.link}"
            >
              <span class="Meta">{{ item.shortTitle }}</span>
              <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
            </a>
          </li>
          <li class="MobileMenu__top__list__item">
            <a
              @click.prevent="handleTopClick('#more')"
              class="MobileMenu__more"
              href="#more"
              :class="{ 'MobileMenu__more--isExtended' : showMore}"
            >More</a>
          </li>
        </ul>
      </div>
      <div class="MobileMenu__subsections">
        <mobile-nav-subsection
          v-if="currentSection"
          :allSubsections="subsections"
          :currentSection="currentSection"
        />
        <mobile-nav-more v-if="showMore" :items="moreContent"/>
      </div>
    </div>
  </div>
</template>
