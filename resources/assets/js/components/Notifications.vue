<script>
import Vue from "vue";
import GoogleMaps from "../services/GoogleMaps";

export default {
  mounted() {},
  props: [],
  mounted() {
    document.addEventListener("showNotification", e => {
      this.content = e.detail.content;
      clearInterval(this.interval);
      this.interval = setInterval(() => {
        this.content = "";
      }, 10000);
    });
  },
  data() {
    return {
      content: "",
      interval: null
    };
  }
};
</script>

<template>
  <div class="Notifications">{{content}}</div>
</template>
