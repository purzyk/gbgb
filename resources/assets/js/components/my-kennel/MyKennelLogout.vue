<script>
import Vue from "vue";
import UserApi from "../../services/UserApi";
import Validation from "../../utils/validation";

export default {
  props: ["nonce"],
  data() {
    return {
      showModal: false,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross,
      popupLogout: window.__GBGB_GLOBAL__.popups.popupLogout
    };
  },
  methods: {
    fireRequest() {
      UserApi.logOut(this.nonce).then(response => {
        if (response.success) {
          window.location.href = window.__GBGB_GLOBAL__.pages.myKennel;
        }
      });
    }
  }
};
</script>

<template>
  <li class="MyKennel--details__actions__item">
    <button
      type="button"
      class="MyKennelButton Button Button--primary__outline"
      @click="showModal=true"
    >
      Log Out
    </button>
    <div class="MyKennelModal" v-if="showModal">
      <div class="MyKennelModal__inner">
        <button type="button" aria-label="Close modal" class="MyKennelModal__close Button" @click="showModal=false">
          <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
        </button>
        <p class="MyKennelModal__title">{{ popupLogout.title }}</p>
        <p class="MyKennelModal__text">{{ popupLogout.text }}</p>
        <div class="MyKennelModal__actions MyKennelModal__row">
          <button
            type="button"
            class="MyKennelButton Button Button--primary__outline"
            @click.stop="showModal=false"
          >{{ popupLogout.cancel_button }}</button>
          <button
            type="button"
            class="MyKennelButton Button Button--primary"
            @click="fireRequest"
          >{{ popupLogout.confirm_button }}</button>
        </div>
      </div>
    </div>
  </li>
</template>
