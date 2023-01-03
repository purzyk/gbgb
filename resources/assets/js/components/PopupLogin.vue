<script>
import Vue from "vue";
import ResultsApi from "../services/ResultsApi";
import MyDogsApi from "../services/MyDogsApi";
var moment = require("moment");

export default {
  props: [],
  created() {},
  data() {
    return {
      loginUrl: window.__GBGB_GLOBAL__.pages.myKennel + "?kennelEndpoint=login",
      registerUrl:
        window.__GBGB_GLOBAL__.pages.myKennel + "?kennelEndpoint=register",
      learnMoreUrl: window.__GBGB_GLOBAL__.pages.myKennel,
      popupLogin: window.__GBGB_GLOBAL__.popups.popupLogin,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross,
      show: false
    };
  },
  methods: {}
};
</script>

<template>
  <div class="PopupWrapper">
    <div class="MyKennelModal" v-if="show">
      <div class="MyKennelModal__inner">
        <button type="button" aria-label="Close modal" class="MyKennelModal__close Button" @click="show=false">
          <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </button>
        <p class="MyKennelModal__title">{{ popupLogin.title }}</p>
        <p class="MyKennelModal__text">{{ popupLogin.text }}</p>
        <div class="MyKennelModal__actions MyKennelModal__row">
          <a :href="registerUrl" class="MyKennelModal__row__button MyKennelButton Button Button--primary__outline">{{ popupLogin.register_button }}</a>
          <a :href="learnMoreUrl" class="MyKennelModal__row__button MyKennelButton Button Button--primary">{{ popupLogin.learn_more_button }}</a>
        </div>
        <div class="MyKennelModal__row">
          <span class="MyKennelModal__inlineLink">{{ popupLogin.login_text }} <a :href="loginUrl">Login</a></span>
        </div>
      </div>
    </div>
  </div>
</template>
