<script>
import Vue from "vue";
import ApiService from "../../services/ApiService";

export default {
  created() {
    this.getAutocompletes();
  },
  data() {
    return {
      autocompletes: {
        towns: [],
        tracknames: [],
        fullnames: []
      },
      town: "",
      trackname: "",
      fullname: "",
      title: "",
      license: "",
      showPopup: false,
      searchIcon: window.__GBGB_GLOBAL__.icons.search,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross
    };
  },
  methods: {
    search() {
      this.$emit("filter", this.buildFilterArray());
      this.showPopup = false;
    },
    getAutocompletes() {
      ApiService.getTrainersAutocompletes().then(response => {
        this.autocompletes = response;
      });
    },
    buildFilterArray() {
      let filters = [];
      if (this.town) {
        filters.push({ name: "trainerTown", value: this.town });
      }
      if (this.trackname) {
        filters.push({ name: "trainerTrackName", value: this.trackname });
      }
      if (this.fullname) {
        filters.push({ name: "trainerFullname", value: this.fullname });
      }
      if (this.title) {
        filters.push({ name: "trainerTitle", value: this.title });
      }
      if (this.license) {
        filters.push({ name: "trainerLicense", value: this.license });
      }
      return filters;
    },
    clear() {
      this.town = "";
      this.trackname = "";
      this.fullname = "";
      this.title = "";
      this.license = "";
      this.search();
    }
  }
};
</script>

<template>
  <div class="TrainersListSearch">
    <button type="button" class="TrainersListSearch__simplified Button" @click="showPopup=true">
      <span v-html="searchIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      <span class="Meta">Search results</span>
    </button>
    <div
      class="TrainersListSearch__wrapper"
      :class="{'TrainersListSearch__wrapper--showPopup' : showPopup}"
    >
      <div class="TrainersListSearch__complex">
        <div class="TrainersListSearchMobileHeader">
          <div class="TrainersListSearchMobileHeader__title">Search</div>
          <button
            type="button"
            class="TrainersListSearchMobileHeader__close Button"
            @click="showPopup=false"
          >
            <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </button>
        </div>
        <animated-select
          v-model="town"
          class="TrainersListSearch__town"
          placeholder="Town"
          :options="autocompletes.towns"
          emptyLabel="All"
        ></animated-select>
        <animated-select
          v-model="trackname"
          class="TrainersListSearch__trackname"
          placeholder="Trackname"
          :options="autocompletes.tracknames"
          emptyLabel="All"
        ></animated-select>
        <span class="TrainersListSearch__typesLabel">Search by:</span>
        <animated-input
          clearButton="true"
          placeholder="Name"
          class="TrainersListSearch__name"
          :autocomplete="autocompletes.fullnames"
          v-model="fullname"
        ></animated-input>
        <div class="TrainersListSearch__actions">
          <button
            type="button"
            @click="clear"
            class="TrainersListSearch__buttonClear Button Button--primary__outline"
          >Clear</button>
          <a @click="search" class="TrainersListSearch__button Button Button--primary">Search</a>
        </div>
      </div>
    </div>
  </div>
</template>
