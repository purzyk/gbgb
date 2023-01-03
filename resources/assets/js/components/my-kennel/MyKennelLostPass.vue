<script>
import Vue from "vue";
import UserApi from "../../services/UserApi";
import Validation from "../../utils/validation";

export default {
  props: ["nonce", "pagetitle"],
  data() {
    return {
      email: "",
      highlightEmail: false,
      errorMsg: "",
      isLoading: false
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
      return errors;
    },
    fireRequest() {
      this.isLoading = true;
      UserApi.lostPass(this.email, this.nonce).then(response => {
        this.isLoading = false;
        if (response.success) {
          this.errorMsg =
            "We'll send the reset link to this email if it exists in our database";
        } else {
          this.errorMsg = "Server error";
        }
      });
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
      <p
        class="MyKennelForm__copy"
      >Enter your email and we'll send you a link to reset your password.</p>
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
          >
          <div class="Field__validationMessage" v-if="highlightEmail">Please enter valid email</div>
        </div>

        <button
          type="button"
          class="MyKennelButton Button Button--primary"
          v-if="errorMsg.length === 0"
          @click="send"
        >
          Reset
          <spinner :isActive="isLoading"/>
        </button>

        <div v-if="errorMsg.length > 0" class="MyKennelForm__error">
          <p class="MyKennelForm__error__text">{{ errorMsg }}</p>
        </div>
      </div>
    </div>
  </div>
</template>
