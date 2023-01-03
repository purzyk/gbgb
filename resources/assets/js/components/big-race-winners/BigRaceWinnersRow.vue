<script>
import Vue from "vue";

export default {
  props: ["data"]
};
</script>

<template>
  <div class="BigRaceWinnersRow">
    <div class="BigRaceWinnersRow__race BigRaceWinnersRow__item">{{ data.race }}</div>
    <div class="BigRaceWinnersRow__track BigRaceWinnersRow__item">{{ data.track }}</div>
    <div class="BigRaceWinnersRow__year BigRaceWinnersRow__item">{{ data.year }}</div>
    <div class="BigRaceWinnersRow__distance BigRaceWinnersRow__item">{{ data.distance }}</div>
    <div class="BigRaceWinnersRow__name BigRaceWinnersRow__item">{{ data.name }}</div>
    <div class="BigRaceWinnersRow__sire BigRaceWinnersRow__item">{{ data.sire }}</div>
    <div class="BigRaceWinnersRow__dam BigRaceWinnersRow__item">{{ data.dam }}</div>
    <div class="BigRaceWinnersRow__trainer BigRaceWinnersRow__item">{{ data.trainer }}</div>
    <div class="BigRaceWinnersRow__time BigRaceWinnersRow__item">{{ data.time }}</div>
    <div class="BigRaceWinnersRow__sp BigRaceWinnersRow__item">{{ data.SP }}</div>
  </div>
</template>
