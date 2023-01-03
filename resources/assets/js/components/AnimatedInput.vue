<script>
import Vue from "vue";
const fuzzysort = require("fuzzysort");

export default {
  props: ["placeholder", "value", "clearButton", "autocomplete"],
  data() {
    return {
      inputValue: this.value,
      autocompleteCurrent: "",
      hasFocus: false,
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
    handleCLick() {
      this.$refs.input.focus();
    },
    handleChange() {
      this.$emit("input", this.inputValue);
    },
    clear() {
      this.inputValue = "";
      this.$emit("input", this.inputValue);
      this.$emit("clear", {});
    },
    handleAutocomplete() {
      if (this.autocomplete) {
        this.inputValue = this.autocompleteCurrent;
        this.handleChange();
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
    }
  },
  watch: {
    value() {
      this.inputValue = this.value;
    }
  },
  computed: {
    autocompleteChoices() {
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
    class="AnimatedInput"
    :class="{ 'AnimatedInput--hasText': inputValue, 'AnimatedInput--hasFocus': hasFocus}"
    @click="handleCLick"
    @touch="handleCLick"
  >
    <div
      class="AnimatedInput__input"
      @keyup.down="handleAutocompleteKey('down')"
      @keyup.up="handleAutocompleteKey('up')"
      @keyup.enter="handleAutocomplete()"
    >
      <span class="AnimatedInput__placeholder">{{ placeholder }}</span>
      <input
        ref="input"
        @change="handleChange"
        @input="handleChange"
        @focus="hasFocus=true"
        @blur="hasFocus=false"
        type="text"
        spellcheck="false"
        autocorrect="off"
        autocomplete="off"
        v-model="inputValue"
      />
      <button type="button" class="AnimatedInput__clear Button" @click="clear" @touch="clear">
        <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
      </button>
      <div
        v-if="autocomplete && autocompleteCurrent !== inputValue"
        class="AnimatedInput__autocomplete"
      >
        <div
          class="AnimatedInput__suggestion"
          :class="{ 'AnimatedInput__suggestion--active': autocompleteCurrent === item }"
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
