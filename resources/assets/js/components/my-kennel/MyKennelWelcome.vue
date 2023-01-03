<script>
import Vue from "vue";

export default {
  props: [
    "pagetitle",
    "introduction",
    "registerlink",
    "registertext",
    "loginlink",
    "logintext",
    "content"
  ]
};
</script>

<template>
  <div class="MyKennel__wrapper">
    <div class="MyKennel__header">
      <mykennel-top-header :disableBack="true" :pagetitle="pagetitle"></mykennel-top-header>
    </div>

    <div class="MyKennel__inner">
      <div v-html="introduction" class="TextBlock"></div>
      <div class="MyKennel__inner__actions">
        <a
          v-bind:href="registerlink"
          class="RegisterButton Button Button--primary"
        >{{ registertext }}</a>
        <a v-bind:href="loginlink" class="LoginButton Button">
          {{ logintext }}
          <span>Login</span>
        </a>
      </div>
      <div v-html="content" class="TextBlock"></div>
    </div>
  </div>
</template>
