<script>
import Vue from "vue";
import $ from "jquery";
import MyCoursesApi from "../services/MyCoursesApi";
import Utils from "../utils/index";

export default {
  props: ["data", "order", "mycourses", "nonce", "nodistance"],
  data() {
    return {
      isExtended: false,
      isFaved:
        this.mycourses.findIndex(element => {
          return (
            parseInt(element.courseId, 10) === parseInt(this.data.courseId, 10)
          );
        }) > -1,
      modal: false,
      isSaving: false,
      starIcon: window.__GBGB_GLOBAL__.icons.star,
      promotionIcon: window.__GBGB_GLOBAL__.icons.promotion,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow,
      chevronIcon: window.__GBGB_GLOBAL__.icons.chevron,
      socialIcons: {
        twitter: window.__GBGB_GLOBAL__.icons.twitter,
        facebook: window.__GBGB_GLOBAL__.icons.facebook,
        envelope: window.__GBGB_GLOBAL__.icons.envelope,
        instagram: window.__GBGB_GLOBAL__.icons.instagram,
        youtube: window.__GBGB_GLOBAL__.icons.youtube
      }
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
    },
    handleButton() {
      if (this.isSaving) {
        return;
      }
      if (!window.__GBGB_GLOBAL__.loggedIn) {
        this.$refs.popupLogin.show = true;
        return;
      }
      if (this.isFaved) {
        this.deleteCourse();
      } else {
        this.addCourse();
      }
    },
    addCourse() {
      this.isSaving = true;
      MyCoursesApi.addCourse(this.nonce, this.data.courseId).then(response => {
        Utils.showNotification("You have favourited your race course");
        this.isSaving = false;
        this.isFaved = true;
      });
    },
    deleteCourse() {
      this.isSaving = true;
      MyCoursesApi.removeCourse(this.nonce, this.data.courseId).then(
        response => {
          Utils.showNotification(
            "You have removed a race course from favourites"
          );
          this.isSaving = false;
          this.isFaved = false;
          this.$emit("deleted", {
            courseId: this.data.courseId,
            hideFunction: this.hide
          });
        }
      );
    },
    displayMediaIcon(icon) {
      return this.socialIcons[icon];
    }
  },
  computed: {
    activePromotions: function() {
      if (!this.nodistance) {
        if (this.data.promotions) {
          return this.data.promotions.filter(function(prom) {
            return prom.public_promotion;
          });
        } else {
          return false;
        }
      } else {
        return this.data.promotions;
      }
    },
    getMeThereLink() {
      return (
        "https://www.google.com/maps/search/?api=1&query=" +
        this.data.location.lat +
        "," +
        this.data.location.lng
      );
    }
  }
};
</script>

<template>
  <div
    class="RaceCourse"
    :class="{'RaceCourse--isExtended':isExtended,'RaceCourse--odd':order % 2}"
    :style="{order: order}"
  >
    <div class="RaceCourse__title_bar" @click="toggle">
      <h3 v-html="data.name" class="RaceCourse__title"></h3>
      <div class="RaceCourse__distance" v-if="data.distance && !nodistance">{{data.distance}} mi</div>
      <div class="RaceCourse__arrow">
        <span v-html="chevronIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </div>
    </div>
    <div class="RaceCourse__inner" ref="inner">
      <div class="RaceCourse__description">{{ data.description }}</div>
      <div class="RaceCourse__contact">
        <p class="RaceCourse__section_header">Contact</p>
        <div class="RaceCourse__phone">{{ data.phone }}</div>
        <a :href="data.email" class="RaceCourse__email">{{ data.email }}</a>
        <a :href="data.link" class="RaceCourse__link">{{ data.link }}</a>
        <address class="RaceCourse__address" v-html="data.address"></address>
        <div class="RaceCourse__social_media">
          <a
            v-for="(item) in data.socialMedia"
            v-bind:key="item.type"
            :href="item.raceCourseSocialMediaLink"
            target="_blank"
            rel="noopener noreferrer"
          >
            <span
              v-html="displayMediaIcon(item.raceCourseSocialMediaType)"
              :class="['SvgContainer Icon Icon--' + item.raceCourseSocialMediaType]"
              aria-hidden="true"
            ></span>
          </a>
        </div>
      </div>
      <div class="RaceCourse__schedule">
        <div class="RaceCourse__section_header">Race times</div>
        <ul>
          <li
            v-if="data.schedule.raceCourseScheduleMonday"
          >Monday {{data.schedule.raceCourseScheduleMonday}}</li>
          <li
            v-if="data.schedule.raceCourseScheduleTuesday"
          >Tuesday {{data.schedule.raceCourseScheduleTuesday}}</li>
          <li
            v-if="data.schedule.raceCourseScheduleWednesday"
          >Wednesday {{data.schedule.raceCourseScheduleWednesday}}</li>
          <li
            v-if="data.schedule.raceCourseScheduleThursday"
          >Thursday {{data.schedule.raceCourseScheduleThursday}}</li>
          <li
            v-if="data.schedule.raceCourseScheduleFriday"
          >Friday {{data.schedule.raceCourseScheduleFriday}}</li>
          <li
            v-if="data.schedule.raceCourseScheduleSaturday"
          >Saturday {{data.schedule.raceCourseScheduleSaturday}}</li>
          <li
            v-if="data.schedule.raceCourseScheduleSunday"
          >Sunday {{data.schedule.raceCourseScheduleSunday}}</li>
        </ul>
      </div>
      <div class="RaceCourse__map">
        <google-map
          :lat="data.location.lat"
          :lng="data.location.lng"
          :address="data.location.address"
        />
      </div>
      <div class="RaceCourse__button">
        <a
          :href="getMeThereLink"
          class="RaceCourse__button__action Button Button--primary__outline"
          target="_blank"
          rel="noopener noreferrer"
        >Get me there</a>
        <a @click="handleButton" class="RaceCourse__button__action Button Button--primary__outline">
          <span v-if="!isFaved" v-html="starIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          <span v-else v-html="starIcon" class="Icon Icon--starred SvgContainer" aria-hidden="true"></span>
          <span v-if="!isFaved" class="Meta">Add to favourites</span>
          <span v-else class="Meta">Remove favourite</span>
        </a>
      </div>
      <div v-if="activePromotions.length > 0" class="RaceCourse__promotions">
        <p class="RaceCourse__promotions__heading">{{ data.promoHeading }}</p>
        <div class="RaceCourse__promotions__items">
          <div v-for="(item) in activePromotions" v-bind:key="item.title" class="PromotionBlock">
            <span
              v-html="promotionIcon"
              class="PromotionBlock__icon SvgContainer"
              aria-hidden="true"
            ></span>
            <div class="PromotionBlock__content">
              <p class="PromotionBlock__content__title">{{ item.title }}</p>
              <p class="PromotionBlock__content__description">{{ item.description }}</p>
              <p class="PromotionBlock__content__expiry-date">Expires {{ item.expiry_date }}</p>
            </div>
            <a
              v-bind:href="item.promotion_url"
              target="_blank"
              rel="noopener noreferrer"
              class="PromotionBlock__link"
            >
              <span
                v-html="arrowIcon"
                class="PromotionBlock__arrow SvgContainer"
                aria-hidden="true"
              ></span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <popup-login ref="popupLogin"></popup-login>
  </div>
</template>
