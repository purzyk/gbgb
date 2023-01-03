<script>
import Vue from "vue";
import ApiService from "../services/ApiService";
import Utils from "../utils/index";

export default {
  created() {
    this.getCourses();
    this.calculateDistances();
  },
  updated() {
    this.calculateDistances();
  },
  props: ["lat", "lng", "status", "mycourses", "nonce"],
  data() {
    return {
      isLoading: true,
      courses: [],
      extendedCourses: []
    };
  },
  methods: {
    getCourses() {
      ApiService.getRaceCourses().then(response => {
        this.courses = response;
        this.calculateDistances();
        this.isLoading = false;
      });
    },
    calculateDistances() {
      if (this.lat && this.lng) {
        this.courses.forEach(course => {
          const distance = Utils.getDistanceHaversine(
            this.lat,
            this.lng,
            course.location.lat,
            course.location.lng
          );
          if (distance < 100) {
            course.distance = parseFloat(distance.toFixed(1));
          } else {
            course.distance = parseInt(distance, 10);
          }
        });
      } else {
        this.courses.forEach(course => {
          course.distance = null;
        });
      }
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
      return this.sortedCourses.indexOf(course);
    }
  },
  computed: {
    sortedCourses() {
      if (this.lat && this.lng) {
        let result = this.courses.concat().sort((a, b) => {
          return a.distance - b.distance;
        });
        return result;
      } else {
        return this.courses;
      }
    }
  }
};
</script>

<template>
  <div class="RaceCourseResults">
    <div class="RaceCourseResults__title" v-if="!status">List of tracks</div>
    <spinner :isActive="isLoading"/>
    <div class="RaceCourseResults__status" v-if="status">{{status}}</div>
    <div class="RaceCourseResults__list" v-if="!status">
      <race-course
        @toggled="handleToggle"
        v-for="(course) in courses"
        v-bind:key="course.name"
        :order="getOrder(course)"
        :data="course"
        :mycourses="mycourses"
        :nonce="nonce"
      ></race-course>
    </div>
  </div>
</template>
