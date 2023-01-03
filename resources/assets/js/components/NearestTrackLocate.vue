<script>
import Vue from "vue";
import GoogleMaps from "../services/GoogleMaps";
import Utils from "../utils/index";

export default {
  created() {
    GoogleMaps.getGoogleMapsAPIObject(this.setupAutocomplete);
  },
  props: ["address", "full"],
  data() {
    return {
      inputVal: this.address,
      lat: null,
      lng: null,
      status: "",
      isLoading: false,
      pageurl: window.__GBGB_GLOBAL__.pages.courses,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow
    };
  },
  methods: {
    handleForm() {
      this.changeStatus("");
      if (this.inputVal === "geolocation") {
        this.geolocate();
        return;
      }
      if (this.inputVal === "") {
        this.lat = null;
        this.lng = null;
        this.displayResults();
      } else {
        GoogleMaps.getGoogleMapsAPIObject(this.geocode);
      }
    },
    setupAutocomplete(maps) {
      const autocomplete = new maps.places.Autocomplete(
        this.$refs.gmapsInput.$refs.input
      );
      autocomplete.setComponentRestrictions({
        country: ["uk"]
      });
      autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        this.lat = place.geometry.location.lat();
        this.lng = place.geometry.location.lng();
        this.inputVal = place.formatted_address;
        this.displayResults();
      });
    },
    geolocate() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
          this.lat = position.coords.latitude;
          this.lng = position.coords.longitude;
          this.inputVal = "geolocation";
          this.displayResultsPage();
          this.displayResults();
        });
      } else {
        this.lat = null;
        this.lng = null;
        this.changeStatus("Localization is not possible on your device");
        this.displayResults();
      }
    },
    geocode(maps) {
      let geocoder = new maps.Geocoder();
      this.isLoading = true;
      geocoder.geocode({ address: this.inputVal }, (results, status) => {
        this.isLoading = false;
        if (status === "OK") {
          this.lat = results[0].geometry.location.lat();
          this.lng = results[0].geometry.location.lng();
          this.displayResultsPage();
        } else {
          this.lat = null;
          this.lng = null;
          this.changeStatus("No results found");
        }
        this.displayResults();
      });
    },
    displayResultsPage() {
      if (this.pageurl && !this.full) {
        window.location.href = Utils.buildUrl(this.pageurl, {
          lat: this.lat,
          lng: this.lng,
          address: this.inputVal
        });
      }
    },
    displayResults() {
      this.$emit("locationChanged", {
        lat: this.lat,
        lng: this.lng,
        status: this.status
      });
    },
    changeStatus(status) {
      this.status = status;
      this.$emit("statusChanged", { status: this.status });
    }
  }
};
</script>

<template>
  <div class="NearestTrackLocate" :class="{ 'NearestTrackLocate--isLoading' :isLoading }">
    <div class="NearestTrackLocate__search" @keyup.enter="handleForm">
      <animated-input
        clearButton="true"
        ref="gmapsInput"
        v-model="inputVal"
        type="text"
        :value="address"
        placeholder="Enter postcode, city"
        @clear="handleForm"
      ></animated-input>
      <button
        type="button"
        class="NearestTrackLocate__search__button Button Button--primary"
        @click="handleForm"
      >
        Find track
        <spinner :isActive="isLoading"/>
      </button>
      <div class="NearestTrackLocate__search__status" v-if="!full">{{status}}</div>
    </div>
    <div class="NearestTrackLocate__links">
      <button
        type="button"
        class="NearestTrackLocate__links__current Button"
        @click="geolocate"
      >Use my current location</button>
      <a :href="pageurl" v-if="!full" class="NearestTrackLocate__links__all">
        <span class="Meta">See all tracks</span>
        <span v-html="arrowIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </a>
    </div>
  </div>
</template>
