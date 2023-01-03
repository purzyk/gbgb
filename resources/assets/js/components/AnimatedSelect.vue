<script>
import Vue from "vue";

export default {
  props: ["placeholder", "value", "options", "disableEmpty", "emptyLabel"],
  data() {
    return {
      inputValue: this.value,
      hasFocus: false,
      triangleIcon: window.__GBGB_GLOBAL__.icons.triangle
    };
  },
  methods: {
    handleChange() {
      this.$emit("input", this.inputValue);
    }
  },
  watch: {
    value() {
      this.inputValue = this.value;
    }
  }
};
</script>

<template>
  <div :value="inputValue" class="AnimatedSelect" :class="{ 'AnimatedSelect--hasText': inputValue}">
    <div class="AnimatedSelect__input">
      <span
        class="AnimatedSelect__placeholder"
        :class="{'AnimatedSelect__placeholder--alwaysUp' : emptyLabel}"
      >{{ placeholder }}</span>
      <select ref="input" @change="handleChange" @input="handleChange" v-model="inputValue">
        <option v-if="!disableEmpty" value>{{emptyLabel}}</option>
        <option
          :key="option.value"
          v-for="(option) in options"
          :value="option.value"
        >{{ option.label }}</option>
      </select>
      <span v-html="triangleIcon" class="Icon SvgContainer" aria-hidden="true"></span>
    </div>
  </div>
</template>

