<script>
import Vue from "vue";
import UserApi from "../../services/UserApi";
import Validation from "../../utils/validation";

export default {
  props: ["nonce", "pagetitle"],
  data() {
    return {
      oldPassword: "",
      password: "",
      confirmPassword: "",
      highlightEmail: false,
      highlightOldPassword: false,
      highlightPassword: false,
      highlightMatch: false,
      errorMsg: "",
      isLoading: false,
      passType1: "password",
      passType2: "password",
      passType3: "password"
    };
  },
  methods: {
    send() {
      const errors = this.hasValidationErrors();
      if (errors === false) {
        this.fireRequest();
      }
    },
    hasValidationErrors() {
      let errors = false;
      if (!Validation.isValidString(this.oldPassword)) {
        this.highlightOldPassword = true;
        errors = true;
      }
      if (!Validation.isValidPassword(this.password)) {
        this.highlightPassword = true;
        errors = true;
      }
      if (this.confirmPassword !== this.password) {
        this.highlightMatch = true;
        errors = true;
      }
      return errors;
    },
    fireRequest() {
      this.isLoading = true;
      UserApi.changePass(
        this.oldPassword,
        this.password,
        this.confirmPassword,
        this.nonce
      ).then(response => {
        this.isLoading = false;
        if (response.success) {
          this.errorMsg = "Password changed successfully!";
        } else {
          this.errorMsg = "It seems that the old password is incorrect";
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
      <div class="MyKennelForm" :class="{'MyKennelForm--isLoading': isLoading}" @keyup.enter="send">
        <div class="MyKennelForm__wrapper">
          <div class="Field">
            <label for="your-old-password" class="Field__label">Old password</label>
            <input
              :type="passType1"
              class="Field__input Field__input--pass"
              :class="{'Field__highlight': highlightOldPassword}"
              id="your-old-firstname"
              v-model="oldPassword"
              @change="highlightOldPassword=false"
            >
            <button
              type="button"
              class="Field__showPass Button"
              @click="passType1 = switchPassType(passType1)"
            >{{passTypeLabel(passType1)}}</button>
            <p
              class="Field__validationMessage"
              v-if="highlightOldPassword"
            >Please enter valid password</p>
          </div>

          <div class="Field">
            <label for="your-new-password1" class="Field__label">New password</label>
            <input
              :type="passType2"
              class="Field__input Field__input--pass"
              :class="{'Field__highlight': highlightPassword || highlightMatch}"
              id="your-new-password1"
              v-model="password"
              @change="highlightPassword=false; highlightMatch=false"
            >
            <button
              type="button"
              class="Field__showPass Button"
              @click="passType2 = switchPassType(passType2)"
            >{{passTypeLabel(passType2)}}</button>
            <p class="Field__validationMessage" v-if="highlightPassword">Please enter valid password</p>
          </div>

          <div class="Field">
            <label for="your-new-password2" class="Field__label">Confirm new password</label>
            <input
              :type="passType3"
              class="Field__input Field__input--pass"
              :class="{'Field__highlight': highlightPassword || highlightMatch}"
              id="your-new-password2"
              v-model="confirmPassword"
              @change="highlightPassword=false; highlightMatch=false"
            >
            <button
              type="button"
              class="Field__showPass Button"
              @click="passType3 =switchPassType(passType3)"
            >{{passTypeLabel(passType3)}}</button>
            <p class="Field__validationMessage" v-if="highlightMatch">Passwords don't match</p>
          </div>

          <button type="button" class="MyKennelButton Button Button--primary" @click="send">
            Change password
            <spinner :isActive="isLoading"/>
          </button>

          <div v-if="errorMsg.length > 0" class="MyKennelForm__error">
            <p class="MyKennelForm__error__text">{{ errorMsg }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
