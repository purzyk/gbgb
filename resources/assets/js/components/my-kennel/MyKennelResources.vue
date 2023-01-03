<script>
import Vue from "vue";

export default {
  props: ["pagetitle", "notices", "resources", "noresults"],
};
</script>

<template>
  <div class="MyKennel__wrapper">
    <div class="MyKennel__header">
      <mykennel-top-header :pagetitle="pagetitle"></mykennel-top-header>
    </div>

    <div class="MyKennel__inner">
      <div class="MyKennelResources">
        <div class="MyKennelResources__row">
          <h2 class="MyKennelResources__row__title">Notices</h2>
          <div v-if="notices.length > 0" class="MyKennelResources__row__items">
            <div
              v-for="(item) in notices"
              v-bind:key="item.notice"
              class="MyKennelNotice"
              v-html="item.notice"
            ></div>
          </div>
          <p v-else v-html="noresults" class="MyKennelResources__row__no-results"></p>
        </div>

        <div class="MyKennelResources__row">
          <h2 class="MyKennelResources__row__title">Resources</h2>
          <div v-if="Object.keys(resources).length > 0">
            <resource-set v-for="(item,role) in resources" v-bind:key="item.title" :title="role">
              <div v-for="(resource) in item" v-bind:key="resource.title" class="Resource">
                <div class="Resource__desc">
                  <h3>{{ resource.title }}</h3>
                  <span v-html="resource.description"></span>
                </div>
                <div class="Resource__button">
                  <a
                    v-bind:href="item.resource"
                    class="MyKennelButton Button Button--primary"
                  >Download</a>
                </div>
              </div>
            </resource-set>
          </div>
          <p v-else v-html="noresults" class="MyKennelResources__row__no-results"></p>
        </div>
      </div>
    </div>
  </div>
</template>
