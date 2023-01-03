<script>
import Vue from "vue";

export default {
  created() {},
  props: ["lat", "lng", "address", "mycourses", "nonce"],
  data() {
    return {
      currentLat: this.lat,
      currentLng: this.lng,
      status: ""
    };
  },
  methods: {
    handleLocationSearch(e) {
      this.currentLat = e.lat;
      this.currentLng = e.lng;
      this.status = e.status;
    }
  }
};
</script>

<template>
  <div class="RaceCourses">
    <div class="RaceCourses__find_a_track">Find a track</div>
    <nearest-track-locate :full="true" @locationChanged="handleLocationSearch" :address="address"/>
    <race-course-results
      :nonce="nonce"
      :mycourses="mycourses"
      :status="status"
      :lat="currentLat"
      :lng="currentLng"
    />
  </div>
</template>
