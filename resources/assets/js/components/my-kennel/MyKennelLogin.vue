<script>
import Vue from "vue";
import UserApi from "../../services/UserApi";
import Validation from "../../utils/validation";

export default {
  props: ["nonce", "pagetitle"],
  data() {
    return {
      email: "",
      password: "",
      highlightEmail: false,
      highlightPassword: false,
      errorMsg: "",
      isLoading: false,
      passType: "password",
      forgotPassUrl:
        window.__GBGB_GLOBAL__.pages.myKennel + "?kennelEndpoint=lostpass"
    };
  },
  methods: {
    send() {
      if (this.isLoading) {
        return;
      }
      const errors = this.hasValidationErrors();
      if (errors === false) {
        this.fireRequest();
      }
    },
    hasValidationErrors() {
      this.errorMsg = "";
      let errors = false;
      this.email = this.email.toLowerCase();
      if (!Validation.isValidEmail(this.email)) {
        this.highlightEmail = true;
        errors = true;
      }
      if (!Validation.isValidString(this.password)) {
        this.highlightPassword = true;
        errors = true;
      }
      return errors;
    },
    fireRequest() {
      this.isLoading = true;
      UserApi.logIn(this.email, this.password, this.nonce).then(response => {
        this.isLoading = false;
        if (response.success) {
          window.location.href = window.__GBGB_GLOBAL__.pages.myKennel;
        } else {
          this.errorMsg = response.message;
        }
      });
    },
    switchPassType(passType) {
      if (passType === "text") {
        return "password";
      }
      return "text";
    },
    passTypeLabel(passType) {
      if (passType === "text") {
        return "Hide";
      }
      return "Show";
    }
  }
};
</script>

<template>
  <div class="MyKennel__wrapper">
    <div class="MyKennel__header">
      <mykennel-top-header :pagetitle="pagetitle"></mykennel-top-header>
    </div>

    <div class="MyKennel__inner">
      <div
        class="MyKennelForm"
        :class="{'MyKennelForm--isLoading' : isLoading}"
        @keyup.enter="send"
      >
        <div class="Field">
          <label for="your-email" class="Field__label">Email</label>
          <input
            type="text"
            class="Field__input"
            :class="{'Field__highlight' : highlightEmail}"
            id="your-email"
            v-model="email"
            @change="highlightEmail=false"
          />
          <div class="Field__validationMessage" v-if="highlightEmail">Please enter valid email</div>
        </div>

        <div class="Field">
          <label for="your-password" class="Field__label">Password</label>
          <input
            :type="passType"
            class="Field__input Field__input--pass"
            :class="{'Field__highlight' : highlightPassword}"
            id="your-password"
            v-model="password"
            @change="highlightPassword=false"
          />
          <button
            type="button"
            class="Field__showPass Button"
            @click="passType=switchPassType(passType)"
          >{{passTypeLabel(passType)}}</button>
          <div class="Field__validationMessage" v-if="highlightPassword">Please enter valid password</div>
        </div>

        <button type="button" class="MyKennelButton Button Button--primary" @click="send">
          Log in
          <spinner :isActive="isLoading" />
        </button>

        <div v-if="errorMsg.length > 0" class="MyKennelForm__error">
          <p class="MyKennelForm__error__text">{{ errorMsg }}</p>
        </div>
        <a class="MyKennelForm__lostPassLink" :href="forgotPassUrl">Forgot your password?</a>
      </div>
    </div>
  </div>
</template>
