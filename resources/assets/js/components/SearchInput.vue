<script>
import Vue from "vue";
const fuzzysort = require("fuzzysort");
import ApiService from "../services/ApiService";
import Utils from "../utils/index";

export default {
  props: ["placeholder", "value"],
  data() {
    return {
      wasChanged: false,
      isLoading: false,
      inputValue: this.value,
      autocomplete: false,
      autocompleteCurrent: "",
      hasFocus: false,
      crossIcon: window.__GBGB_GLOBAL__.icons.crossBig,
      searchIcon: window.__GBGB_GLOBAL__.icons.search,
      searchUrl: window.__GBGB_GLOBAL__.pages.searchUrl
    };
  },
  mounted() {
    ["keyup", "keydown", "keypress", "focus", "blur"].map(event => {
      this.$refs.input.addEventListener(event, $event =>
        this.$emit(event, $event)
      );
    });
    this.$refs.input.value = this.value;
  },
  methods: {
    getAutocomplete() {
      this.isLoading = true;
      ApiService.getSearchAutocomplete().then(response => {
        this.autocomplete = response;
        this.isLoading = false;
      });
    },
    handleCLick() {
      this.$refs.input.focus();
    },
    handleChange() {
      this.inputValue = this.$refs.input.value;
      this.$emit("input", this.inputValue);
    },
    clear() {
      this.inputValue = "";
      this.$emit("input", this.inputValue);
      this.$emit("clear", {});
    },
    handleAutocomplete() {
      if (this.autocompleteCurrent === "") {
        this.search();
        return;
      }
      if (this.autocomplete) {
        this.$refs.input.value = this.autocompleteCurrent;
        this.handleChange();
        this.search();
      }
    },
    handleAutocompleteKey(key) {
      if (!this.autocomplete) {
        return;
      }
      let currentIndex = this.autocompleteChoices.indexOf(
        this.autocompleteCurrent
      );
      if (key === "down") {
        currentIndex++;
      } else {
        currentIndex--;
      }
      if (currentIndex < 0) {
        currentIndex = 0;
      }
      if (currentIndex >= this.autocompleteChoices.length) {
        currentIndex = 0;
      }
      this.autocompleteCurrent = this.autocompleteChoices[currentIndex];
    },
    search() {
      if (this.inputValue.length < 1) {
        return;
      }
      this.isLoading = true;
      window.location.href = Utils.buildUrl(this.searchUrl, {
        s: this.inputValue
      });
    }
  },
  watch: {
    value() {
      this.inputValue = this.value;
    },
    inputValue() {
      this.$refs.input.value = this.inputValue;
      this.wasChanged = true;
      if (
        this.inputValue.length > 0 &&
        this.autocomplete === false &&
        this.isLoading === false
      ) {
        this.getAutocomplete();
      }
    }
  },
  computed: {
    autocompleteChoices() {
      if (this.inputValue.length < 3) {
        return [];
      }
      let choices = [];
      const results = fuzzysort.go(this.inputValue, this.autocomplete);
      results.map(element => {
        choices.push(element.target);
      });
      return choices.slice(0, 7);
    }
  }
};
</script>

<template>
  <div
    :value="inputValue"
    class="SearchInput"
    :class="{ 'SearchInput--hasText': inputValue, 'SearchInput--hasFocus': hasFocus}"
    @click="handleCLick"
    @touch="handleCLick"
  >
    <div
      class="SearchInput__input"
      @keyup.down="handleAutocompleteKey('down')"
      @keyup.up="handleAutocompleteKey('up')"
      @keyup.enter="handleAutocomplete()"
    >
      <input
        :placeholder="placeholder"
        ref="input"
        @change="handleChange"
        @input="handleChange"
        @focus="hasFocus=true"
        @blur="hasFocus=false"
        type="text"
      >
      <button type="button" class="SearchInput__clear Button" @click="clear" @touch="clear">
        <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </button>
      <button type="button" class="SearchInput__go Button" @click="search" @touch="search">
        <span v-html="searchIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </button>
      <div v-if="autocomplete && !isLoading && wasChanged" class="SearchInput__autocomplete">
        <div
          class="SearchInput__suggestion"
          :class="{ 'SearchInput__suggestion--active': autocompleteCurrent === item }"
          :key="`row-${index}`"
          @click="handleAutocomplete"
          @touch="handleAutocomplete"
          @mouseover="autocompleteCurrent=item"
          v-for="(item,index) in autocompleteChoices"
        >{{ item }}</div>
      </div>
    </div>
  </div>
</template>
