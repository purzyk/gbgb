<script>
import Vue from "vue";
const fuzzysort = require("fuzzysort");
import ResultsApi from "../services/ResultsApi";

export default {
  props: ["placeholder"],
  data() {
    return {
      greyhoundName: "",
      greyhoundId: null,
      autocompleteCurrent: "",
      hasFocus: false,
      autocomplete: [],
      crossIcon: window.__GBGB_GLOBAL__.icons.crossBig
    };
  },
  mounted() {
    ["keyup", "keydown", "keypress", "focus", "blur"].map(event => {
      this.$refs.input.addEventListener(event, $event =>
        this.$emit(event, $event)
      );
    });
  },
  methods: {
    handleClick() {
      this.$refs.input.focus();
    },
    handleChange() {
      this.greyhoundId = null;
      this.greyhoundName = this.$refs.input.value;
      this.$emit("change", { greyhoundName: "", greyhoundId: null });
    },
    clear() {
      this.greyhoundName = "";
      this.greyhoundId = null;
      this.$emit("change", { greyhoundName: "", greyhoundId: null });
      this.$emit("clear", {});
    },
    handleAutocomplete() {
      if (this.autocomplete) {
        this.greyhoundName = this.autocompleteCurrent.obj.dogName;
        this.greyhoundId = this.autocompleteCurrent.obj.dogId;
        this.$emit("change", {
          greyhoundId: this.greyhoundId,
          greyhoundName: this.greyhoundName
        });
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
    getAutocomplete() {
      ResultsApi.getGreyhoundAutocomplete(this.greyhoundName + "%20").then(
        response => {
          this.autocomplete = response;
        }
      );
    },
    loadGreyhound(greyhoundName, greyhoundId) {
      this.greyhoundName = greyhoundName;
      this.greyhoundId = greyhoundId;
    }
  },
  watch: {
    greyhoundName() {
      if (this.greyhoundName.length < 2) {
        this.autocomplete = [];
      } else if (this.autocomplete.length < 1) {
        this.getAutocomplete();
      }
      this.$refs.input.value = this.greyhoundName;
    }
  },
  computed: {
    autocompleteChoices() {
      let choices = [];
      const results = fuzzysort.go(this.greyhoundName, this.autocomplete, {
        key: "dogName"
      });
      this.autocompleteCurrent = results[0];
      return results.slice(0, 7);
    }
  }
};
</script>

<template>
  <div
    class="GreyhoundInput"
    :class="{ 'GreyhoundInput--hasText': greyhoundName, 'GreyhoundInput--hasFocus': hasFocus, 'GreyhoundInput--validValue': greyhoundId}"
    v-on:click="handleClick"
  >
    <div
      class="GreyhoundInput__input"
      @keyup.down="handleAutocompleteKey('down')"
      @keyup.up="handleAutocompleteKey('up')"
      @keyup.enter="handleAutocomplete()"
    >
      <span class="GreyhoundInput__placeholder">{{ placeholder }}</span>
      <input
        ref="input"
        @change="handleChange"
        @input="handleChange"
        @focus="hasFocus=true"
        @blur="hasFocus=false"
        type="text"
      >
      <button type="button" class="GreyhoundInput__clear Button" @click="clear" @touch="clear">
        <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </button>
      <p
        v-if="!greyhoundId && !hasFocus"
        class="GreyhoundInput__message"
      >Select a greyhound from list</p>
    </div>
    <div v-if="autocomplete && !greyhoundId" class="GreyhoundInput__autocomplete">
      <div
        class="GreyhoundInput__suggestion"
        :class="{ 'GreyhoundInput__suggestion--active': autocompleteCurrent === item }"
        :key="`row-${index}`"
        @click="handleAutocomplete"
        @mouseover="autocompleteCurrent=item"
        v-for="(item,index) in autocompleteChoices"
      >{{ item.obj.dogName }}</div>
    </div>
  </div>
</template>
