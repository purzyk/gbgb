<script>
import Vue from "vue";
import MyDogsApi from "../../services/MyDogsApi";
import Utils from "../../utils/index";

export default {
  props: ["nonce", "name", "pagetitle"],
  created() {},
  data() {
    return {
      isSaving: false,
      dogs: window.__GBGB_GLOBAL__.myDogs,
      findGrehoundFormExpanded: false,
      myKennelUrl: window.__GBGB_GLOBAL__.pages.myKennel,
      arrowIcon: window.__GBGB_GLOBAL__.icons.arrow,
      isLoadingGreyhound: false,
      loadQueue: []
    };
  },
  methods: {
    handleButton() {
      this.findGrehoundFormExpanded = true;
    },
    handleDelete(e) {
      this.dogs = this.dogs.filter(element => {
        return element.dogId !== e.dogId;
      });
    },
    addGreyhound(e) {
      if (this.isSaving) {
        return;
      }
      this.isSaving = true;
      MyDogsApi.addDog(this.nonce, e.dogId).then(response => {
        Utils.showNotification("You have added a greyhound to your kennel");
        this.dogs = response.data;
        this.isSaving = false;
      });
    },
    handleRequestLoad(e) {
      this.loadQueue.push(e);
      if (this.isLoadingGreyhound === false) {
        this.loadNext();
      }
    },
    handleLoaded() {
      this.isLoadingGreyhound = false;
      this.loadNext();
    },
    loadNext() {
      if (this.loadQueue.length > 0) {
        const request = this.loadQueue.shift();
        this.isLoadingGreyhound = true;
        request.callback.call();
      }
    }
  }
};
</script>

<template>
  <div class="MyKennel__wrapper">
    <div class="MyKennel__header">
      <mykennel-top-header :pagetitle="pagetitle"></mykennel-top-header>
      <div v-if="dogs.length > 0" class="MyDogsAdd">
        <button
          type="button"
          class="MyKennel__header__button MyKennelButton Button Button--white__outline"
          @click="handleButton"
          v-if="!findGrehoundFormExpanded"
        >+ Add a greyhound</button>
        <find-a-greyhound
          @addGreyhound="addGreyhound"
          mode="add"
          v-if="findGrehoundFormExpanded"
          cta="Add a greyhound"
        ></find-a-greyhound>
      </div>
    </div>

    <div class="MyKennel__inner">
      <div v-if="dogs.length > 0" class="MyDogs">
        <p v-if="name" v-html="name" class="MyDogs__name"></p>
        <div class="MyDogs__row">
          <my-dogs-row
            :nonce="nonce"
            v-for="(dog) in dogs"
            v-bind:key="dog.dogId"
            :data="dog"
            @deleted="handleDelete"
            @requestLoad="handleRequestLoad"
            @loaded="handleLoaded"
            @failed="handleLoaded"
          ></my-dogs-row>
        </div>
      </div>
      <div class="MyDogsEmpty" v-if="dogs.length < 1">
        <p class="MyDogsEmpty__text">Add a greyhound to list</p>
        <button
          type="button"
          class="MyKennelButton Button Button--primary__outline"
          @click="handleButton"
          v-if="!findGrehoundFormExpanded"
        >+ Add a greyhound</button>
        <find-a-greyhound
          @addGreyhound="addGreyhound"
          mode="add"
          v-if="findGrehoundFormExpanded"
          cta="Add a greyhound"
        ></find-a-greyhound>
      </div>
    </div>
  </div>
</template>
