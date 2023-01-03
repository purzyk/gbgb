<script>
import Vue from "vue";
import ResultsApi from "../services/ResultsApi";
import MyDogsApi from "../services/MyDogsApi";
var moment = require("moment");

export default {
  props: ["yesMethod", "noMethod"]
};
</script>

<template>
  <div class="MyKennelModal">
    <div class="MyKennelModal__inner">
      <div
        class="MyKennelModal__row"
      >Are you sure you want to remove this greyhound from your kennel?</div>
      <div class="MyKennelModal__actions MyKennelModal__row">
        <a class="MyKennelButton Button Button--primary__outline" @click.stop="noMethod">No</a>
        <a class="MyKennelButton Button Button--primary" @click.stop="yesMethod">Yes</a>
      </div>
    </div>
  </div>
</template>
