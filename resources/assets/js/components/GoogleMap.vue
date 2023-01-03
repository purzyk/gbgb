<script>
import Vue from "vue";
import GoogleMaps from "../services/GoogleMaps";

export default {
  mounted() {
    GoogleMaps.getGoogleMapsAPIObject(this.loadMap);
  },
  props: ["lat", "lng", "address"],
  data() {
    return {};
  },
  methods: {
    loadMap(maps) {
      const coords = new maps.LatLng(this.lat, this.lng);
      const map = new maps.Map(this.$refs.mapElement, {
        zoom: 16,
        center: coords,
        mapTypeId: maps.MapTypeId.ROADMAP
      });
      map.markers = [];
      const marker = new maps.Marker({
        position: coords,
        map: map
      });
      map.markers.push(marker);
    }
  }
};
</script>

<template>
  <div class="GoogleMap">
    <div class="GoogleMap__inner" ref="mapElement"></div>
  </div>
</template>
