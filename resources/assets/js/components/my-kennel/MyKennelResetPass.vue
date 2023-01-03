<script>
import Vue from "vue";
import UserApi from "../../services/UserApi";
import Validation from "../../utils/validation";

export default {
  props: ["nonce", "pagetitle", "token", "noncelogin"],
  data() {
    return {
      password: "",
      highlightPassword: false,
      errorMsg: "",
      isLoading: false,
      passType: "password"
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
      if (!Validation.isValidPassword(this.password)) {
        this.highlightPassword = true;
        errors = true;
      }
      return errors;
    },
    fireRequest() {
      this.isLoading = true;
      UserApi.resetPass(this.token, this.password, this.nonce).then(
        response => {
          this.isLoading = false;
          if (response.success) {
            this.errorMsg = "Your password has been reset,logging you in...";
            this.logIn(response.message);
          } else {
            this.errorMsg =
              "Error. Are you sure you've used a correct password reset link?";
          }
        }
      );
    },
    logIn(email) {
      this.isLoading = true;
      UserApi.logIn(email, this.password, this.noncelogin).then(response => {
        this.isLoading = false;
        if (response.success) {
          window.location.href = window.__GBGB_GLOBAL__.pages.myKennel;
        } else {
          this.errorMsg = "Server error";
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
      <p class="MyKennelForm__copy">Please enter new password.</p>
      <div
        class="MyKennelForm"
        :class="{'MyKennelForm--isLoading' : isLoading}"
        @keyup.enter="send"
      >
        <div class="Field">
          <label for="your-password" class="Field__label">Password</label>
          <input
            :type="passType"
            class="Field__input Field__input--pass"
            :class="{'Field__highlight' : highlightPassword}"
            id="your-password"
            v-model="password"
            @change="highlightPassword=false;highlightMatch=false"
          >
          <button
            type="button"
            class="Field__showPass Button"
            @click="passType=switchPassType(passType)"
          >{{passTypeLabel(passType)}}</button>
          <p class="Field__validationMessage" v-if="highlightPassword">Please enter valid password</p>
          <p class="Field__passInfo">
            This should include a minimum of 8 characters, 1 number, 1 lowercase,
            1 capital letter & 1 special character
            <span
              class="Field__passInfo_chars"
            >
              Accepted characters:
              <br>
              !""#$%&'()*+,-./\:;?@[\]^_`{|}~"
            </span>
          </p>
        </div>

        <button
          type="button"
          class="MyKennelButton Button Button--primary"
          v-if="errorMsg.length === 0"
          @click="send"
        >
          Set new password
          <spinner :isActive="isLoading"/>
        </button>

        <div v-if="errorMsg.length > 0" class="MyKennelForm__error">
          <p class="MyKennelForm__error__text">{{ errorMsg }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
