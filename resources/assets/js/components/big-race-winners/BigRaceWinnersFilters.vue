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
        years: [],
        races: [],
        names: [],
        sires: [],
        dames: [],
        trainers: []
      },
      year: "",
      race: "",
      name: "",
      types: [
        { value: "names", label: "Greyhound" },
        { value: "sires", label: "Sire" },
        { value: "dams", label: "Dam" },
        { value: "trainers", label: "Trainer" }
      ],
      type: "names",
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
      ApiService.getBigRaceWinnersAutocompletes().then(response => {
        this.autocompletes = response;
      });
    },
    buildFilterArray() {
      let filters = [];
      if (this.year) {
        filters.push({ name: "bigRaceWinnerYear", value: this.year });
      }
      if (this.race) {
        filters.push({ name: "bigRaceWinnerRace", value: this.race });
      }
      if (this.name) {
        switch (this.type) {
          case "names":
            filters.push({ name: "bigRaceWinnerName", value: this.name });
            break;
          case "sires":
            filters.push({ name: "bigRaceWinnerSire", value: this.name });
            break;
          case "dams":
            filters.push({ name: "bigRaceWinnerDam", value: this.name });
            break;
          case "trainers":
            filters.push({ name: "bigRaceWinnerTrainer", value: this.name });
            break;
          default:
            break;
        }
      }
      return filters;
    },
    clear() {
      this.year = "";
      this.race = "";
      this.name = "";
      this.search();
    }
  },
  computed: {
    inputPlaceholder() {
      return this.types.find(element => element.value === this.type).label;
    }
  }
};
</script>

<template>
  <div class="BigRaceWinnersSearch">
    <button type="button" class="BigRaceWinnersSearch__simplified Button" @click="showPopup=true">
      <span v-html="searchIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      <span class="Meta">Search results</span>
    </button>
    <div
      class="BigRaceWinnersSearch__wrapper"
      :class="{'BigRaceWinnersSearch__wrapper--showPopup' : showPopup}"
    >
      <div class="BigRaceWinnersSearch__complex">
        <div class="BigRaceWinnersSearchMobileHeader">
          <div class="BigRaceWinnersSearchMobileHeader__title">Search</div>
          <button
            type="button"
            class="BigRaceWinnersSearchMobileHeader__close Button"
            @click="showPopup=false"
          >
            <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
          </button>
        </div>
        <animated-select
          v-model="year"
          class="BigRaceWinnersSearch__year"
          placeholder="Year"
          :options="autocompletes.years"
          emptyLabel="All"
        ></animated-select>
        <animated-select
          v-model="race"
          class="BigRaceWinnersSearch__race"
          placeholder="Race"
          :options="autocompletes.races"
          emptyLabel="All"
        ></animated-select>
        <span class="BigRaceWinnersSearch__typesLabel">Search by:</span>
        <animated-select
          v-model="type"
          class="BigRaceWinnersSearch__type"
          placeholder="By"
          :options="types"
          :disableEmpty="true"
        ></animated-select>
        <animated-input
          class="BigRaceWinnersSearch__searchBy"
          clearButton="true"
          :placeholder="inputPlaceholder"
          :autocomplete="autocompletes[type]"
          v-model="name"
        ></animated-input>
        <div class="BigRaceWinnersSearch__actions">
          <button
            type="button"
            @click="clear"
            class="BigRaceWinnersSearch__buttonClear Button Button--primary__outline"
          >Clear</button>
          <a @click="search" class="BigRaceWinnersSearch__button Button Button--primary">Search</a>
        </div>
      </div>
    </div>
  </div>
</template>
