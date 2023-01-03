<script>
import Vue from "vue";
import ApiService from "../../services/ApiService";
import MyDogsApi from "../../services/MyCoursesApi";

export default {
  props: ["nonce", "pagetitle", "courses"],
  created() {
    this.getCourses();
  },
  data() {
    return {
      isLoading: true,
      currentCourses: this.courses,
      courseData: [],
      addCourseFormExpanded: false,
      coursesUrl: window.__GBGB_GLOBAL__.pages.courses,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow,
      extendedCourses: []
    };
  },
  methods: {
    getCourses() {
      ApiService.getRaceCourses().then(response => {
        this.courseData = response;
        this.isLoading = false;
      });
    },
    handleButton() {
      this.addCourseFormExpanded = true;
    },
    handleDelete(e) {
      const currentCourses = this.currentCourses;
      this.currentCourses = [];
      this.isLoading = true;
      setTimeout(() => {
        this.currentCourses = currentCourses.filter(element => {
          return parseInt(element.courseId, 10) !== parseInt(e.courseId, 10);
        });
        this.isLoading = false;
      }, 500);
    },
    handleToggle(e) {
      if (e.isExtended) {
        if (this.extendedCourses.length >= 3) {
          this.extendedCourses.forEach(course => {
            course.hide();
          });
          this.extendedCourses = [];
        }
        this.extendedCourses.push(e.component);
      } else {
        let index = this.extendedCourses.indexOf(e.component);
        if (index !== -1) this.extendedCourses.splice(index, 1);
      }
    },
    getOrder(course) {
      return this.currentCourses.indexOf(course);
    },
    getData(courseId) {
      return this.courseData.find(element => {
        return parseInt(element.courseId, 10) === parseInt(courseId, 10);
      });
    }
  }
};
</script>

<template>
  <div class="MyKennel__wrapper">
    <div class="MyKennel__header">
      <mykennel-top-header :pagetitle="pagetitle"></mykennel-top-header>
      <div class="MyCoursesAdd" v-if="currentCourses.length > 0">
        <a
          type="button"
          class="MyKennel__header__button MyKennelButton Button Button--white__outline"
          :href="coursesUrl"
          v-if="!addCourseFormExpanded"
        >+ Add a racecourse</a>
      </div>
    </div>

    <div class="MyKennel__inner">
      <div class="RaceCourseResults__list" v-if="!isLoading">
        <race-course
          @toggled="handleToggle"
          @deleted="handleDelete"
          v-for="(course) in currentCourses"
          v-bind:key="course.name"
          :order="getOrder(course)"
          :data="getData(course.courseId)"
          :mycourses="currentCourses"
          :nodistance="true"
          :nonce="nonce"
        ></race-course>
      </div>
      <div class="MyCoursesEmpty" v-if="currentCourses.length < 1 && !isLoading">
        Add your favourite racecourses
        <a
          class="MyKennelButton Button Button--primary__outline"
          :href="coursesUrl"
          v-if="!addCourseFormExpanded"
        >+ Add a racecourse</a>
      </div>
      <spinner :isActive="isLoading" />
    </div>
  </div>
</template>
