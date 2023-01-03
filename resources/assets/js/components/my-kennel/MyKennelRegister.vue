<script>
import Vue from "vue";
import UserApi from "../../services/UserApi";
import Validation from "../../utils/validation";

export default {
  props: ["nonce", "noncelogin", "pagetitle", "intro"],
  data() {
    return {
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      tc: false,
      highlightFirstName: false,
      highlightLastName: false,
      highlightEmail: false,
      highlightPassword: false,
      highlightTc: false,
      errorField: "",
      errorMsg: "",
      isLoading: false,
      passType: "password",
      stage: "form",
      myKennelUrl: window.__GBGB_GLOBAL__.pages.myKennel,
      forgotPassUrl:
        window.__GBGB_GLOBAL__.pages.myKennel + "?kennelEndpoint=lostpass",
      tcPage: window.__GBGB_GLOBAL__.pages.tc,
      crossIcon: window.__GBGB_GLOBAL__.icons.cross,
      showConfirmationModal: false,
      showSuccessModal: false
    };
  },
  methods: {
    send() {
      if (this.isLoading || this.stage !== "form") {
        return;
      }
      const errors = this.hasValidationErrors();
      if (errors === false) {
        this.stage = "confirmation";
        this.showConfirmationModal = true;
      }
    },
    hasValidationErrors() {
      this.errorMsg = "";
      this.errorFirstnameField = "";
      this.errorLastnameField = "";
      let errors = false;
      if (!Validation.isValidString(this.firstName)) {
        this.highlightFirstName = true;
        this.errorFirstnameField = "Please enter your first name";
        errors = true;
      }
      if (!Validation.isLength(this.firstName, 30)) {
        this.highlightFirstName = true;
        this.errorFirstnameField = "Your first name is too long";
        errors = true;
      }
      if (!Validation.isValidString(this.lastName)) {
        this.highlightLastName = true;
        this.errorLastnameField = "Please enter your last name";
        errors = true;
      }
      if (!Validation.isLength(this.lastName, 30)) {
        this.highlightLastName = true;
        this.errorLastnameField = "Your last name is too long";
        errors = true;
      }
      this.email = this.email.toLowerCase();
      if (!Validation.isValidEmail(this.email)) {
        this.highlightEmail = true;
        errors = true;
      }
      if (!Validation.isValidPassword(this.password)) {
        this.highlightPassword = true;
        errors = true;
      }
      if (!this.tc) {
        this.highlightTc = true;
        errors = true;
      }
      return errors;
    },
    fireRequest() {
      const errors = this.hasValidationErrors();
      if (errors) {
        return;
      }
      this.isLoading = true;
      this.stage = "pending";
      UserApi.register(
        this.email,
        this.password,
        this.password,
        this.firstName,
        this.lastName,
        this.nonce
      ).then(response => {
        this.isLoading = false;
        if (response.success) {
          this.stage = "success";
          this.showSuccessModal = true;
        } else {
          this.stage = "error";
          this.errorMsg = "This email is already registered with us";
        }
      });
    },
    logIn() {
      this.isLoading = true;
      UserApi.logIn(this.email, this.password, this.noncelogin).then(
        response => {
          this.isLoading = false;
          if (response.success) {
            window.location.href = window.__GBGB_GLOBAL__.pages.myKennel;
          } else {
            this.errorMsg = response.message;
          }
        }
      );
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
        <p class="MyKennelForm__copy">{{ intro }}</p>
        <div class="MyKennelForm__wrapper">
          <div class="Field">
            <label for="your-firstname" class="Field__label">First name</label>
            <input
              type="text"
              class="Field__input"
              :class="{'Field__highlight' : highlightFirstName}"
              id="your-firstname"
              v-model="firstName"
              @change="highlightFirstName=false"
            >
            <p
              class="Field__validationMessage"
              v-if="highlightFirstName"
              v-html="errorFirstnameField"
            ></p>
          </div>

          <div class="Field">
            <label for="your-lastname" class="Field__label">Last name</label>
            <input
              type="text"
              class="Field__input"
              :class="{'Field__highlight' : highlightLastName}"
              id="your-lastname"
              v-model="lastName"
              @change="highlightLastName=false"
            >
            <p
              class="Field__validationMessage"
              v-if="highlightLastName"
              v-html="errorLastnameField"
            ></p>
          </div>

          <div class="Field">
            <label for="your-email" class="Field__label">Email address</label>
            <input
              type="email"
              class="Field__input"
              :class="{'Field__highlight' : highlightEmail}"
              id="your-email"
              v-model="email"
              @change="highlightEmail=false"
            >
            <p
              class="Field__validationMessage"
              v-if="highlightEmail"
            >Please enter valid email address</p>
          </div>

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

          <div class="Field Field--checkbox">
            <label
              class="Field__checkbox"
              @click="tc=!tc;highlightTc=false"
              :class="{'Field__highlight' : highlightTc, 'Field__checkbox--active' : tc}"
            >
              <span>
                I agree to the
                <a :href="tcPage">terms & conditions</a>
              </span>
            </label>
            <p class="Field__validationMessage" v-if="highlightTc">You must agree to continue</p>
          </div>

          <button type="button" class="MyKennelButton Button Button--primary" @click="send">
            Create account
            <spinner :isActive="isLoading"/>
          </button>

          <div class="MyKennelModal" v-if="stage === 'confirmation' && showConfirmationModal">
            <div class="MyKennelModal__inner">
              <button
                type="button"
                aria-label="Close modal"
                class="MyKennelModal__close Button"
                @click="showConfirmationModal=false"
              >
                <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
              </button>
              <p class="MyKennelModal__title">Confirmation</p>
              <p class="MyKennelModal__text">Is this the right email address?</p>
              <div class="MyKennelModal__row MyKennelModal__row--email">
                <input
                  type="email"
                  class="Field__input"
                  :class="{'Field__highlight' : highlightEmail}"
                  id="your-email"
                  v-model="email"
                  @change="highlightEmail=false"
                >
                <p
                  class="Field__validationMessage"
                  v-if="highlightEmail"
                >Please enter valid email address</p>
              </div>
              <div class="MyKennelModal__actions MyKennelModal__row">
                <button
                  type="button"
                  class="MyKennelModal__row__button MyKennelButton Button Button--primary__outline"
                  @click="stage='form'"
                >Go back</button>
                <button
                  type="button"
                  class="MyKennelModal__row__button MyKennelButton Button Button--primary"
                  @click="fireRequest"
                >Yes</button>
              </div>
            </div>
          </div>

          <div class="MyKennelModal" v-if="stage === 'success' && showSuccessModal">
            <div class="MyKennelModal__inner">
              <button
                type="button"
                aria-label="Close modal"
                class="MyKennelModal__close Button"
                @click="showSuccessModal=false"
              >
                <span v-html="crossIcon" class="Icon SvgContainer" aria-hidden="true"></span>
              </button>
              <p class="MyKennelModal__title">Success</p>
              <p class="MyKennelModal__text">Your kennel has been created!</p>
              <div class="MyKennelModal__actions MyKennelModal__row">
                <button
                  type="button"
                  class="MyKennelModal__row__button MyKennelButton Button Button--primary"
                  @click="logIn"
                >
                  Log in
                  <spinner :isActive="isLoading"/>
                </button>
              </div>
            </div>
          </div>

          <div class="MyKennelModal" v-if="stage === 'error'">
            <div class="MyKennelModal__inner">
              <div class="MyKennelModal__row MyKennelModal__row--error" v-html="errorMsg"></div>
              <div class="MyKennelModal__row">
                <a :href="myKennelUrl" class="MyKennelButton">Log in</a>
              </div>
              <div class="MyKennelModal__row">
                <a class="MyKennelModal__link" :href="forgotPassUrl">Forgot your password</a>
                <a class="MyKennelModal__link" @click="stage='form'">Cancel</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
