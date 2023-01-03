<script>
import Vue from "vue";

export default {
  props: ["data"],
  methods: {
    callHref(telephoneNumber) {
      if (telephoneNumber[0] !== "0") {
        telephoneNumber = "0" + telephoneNumber;
      }
      return telephoneNumber.length > 3 ? "tel:" + telephoneNumber : "#";
    }
  }
};
</script>

<template>
  <div class="TrainersListRow">
    <div class="TrainersListRow__fullname TrainersListRow__item">{{ data.fullname }}</div>
    <div class="TrainersListRow__title TrainersListRow__item">{{ data.title }}</div>
    <div class="TrainersListRow__trackname TrainersListRow__item">{{ data.trackname }}</div>
    <div class="TrainersListRow__license TrainersListRow__item">{{ data.license }}</div>
    <div class="TrainersListRow__town TrainersListRow__item">{{ data.town }}</div>
    <div class="TrainersListRow__telno TrainersListRow__item">
      <a :href="callHref(data.tel_number)">{{ data.tel_number }}</a>
    </div>
    <div class="TrainersListRow__mobilephoneno TrainersListRow__item">
      <a :href="callHref(data.mobile_number)">{{ data.mobile_number }}</a>
    </div>
  </div>
</template>
