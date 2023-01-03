import $ from "jquery";
window.$ = $;
window.jQuery = $;
import "./lib/viewport";
import Vue from "vue";

import { polyfill } from "es6-promise";
polyfill();
import Polyfills from "./utils/polyfills";
Polyfills.polyfillAll();

import LiveResults from "./components/live-results/LiveResults";
import LiveResultsTable from "./components/live-results/LiveResultsTable";
import LiveResultsRow from "./components/live-results/LiveResultsRow";
import LiveResultsSearch from "./components/live-results/LiveResultsSearch";
import LiveResultsPagination from "./components/live-results/LiveResultsPagination";

import SocialMediaFeed from "./components/SocialMediaFeed";
import SocialMediaPost from "./components/SocialMediaPost";

import Masonry from "./components/Masonry";
import MasonryItem from "./components/MasonryItem";

import AnimatedInput from "./components/AnimatedInput";
import SearchInput from "./components/SearchInput";
import GreyhoundInput from "./components/GreyhoundInput";
import AnimatedSelect from "./components/AnimatedSelect";
import Spinner from "./components/Spinner";
import NearestTrackLocate from "./components/NearestTrackLocate";

import MobileNav from "./components/mobile-nav/MobileNav";
import MobileNavSubsection from "./components/mobile-nav/MobileNavSubsection";
import MobileNavMore from "./components/mobile-nav/MobileNavMore";
import Notifications from "./components/Notifications";

import FindAGreyhound from "./components/FindAGreyhound";

Vue.component("live-results", LiveResults);
Vue.component("live-results-table", LiveResultsTable);
Vue.component("live-results-row", LiveResultsRow);
Vue.component("live-results-search", LiveResultsSearch);
Vue.component("live-results-pagination", LiveResultsPagination);

Vue.component("social-media-feed", SocialMediaFeed);
Vue.component("social-media-post", SocialMediaPost);

Vue.component("masonry", Masonry);
Vue.component("masonry-item", MasonryItem);

Vue.component("animated-input", AnimatedInput);
Vue.component("search-input", SearchInput);
Vue.component("greyhound-input", GreyhoundInput);
Vue.component("animated-select", AnimatedSelect);
Vue.component("spinner", Spinner);
Vue.component("nearest-track-locate", NearestTrackLocate);

Vue.component("mobile-nav", MobileNav);
Vue.component("mobile-nav-subsection", MobileNavSubsection);
Vue.component("mobile-nav-more", MobileNavMore);
Vue.component("notifications", Notifications);

Vue.component("find-a-greyhound", FindAGreyhound);

import Hero from "./jq-components/hero";
import HeaderNav from "./jq-components/header-nav";
import BackToTop from "./jq-components/backToTop";
import Modal from "./jq-components/modal";

import BlockAnimations from "./animations/BlockAnimations";

Vue.filter("dashEmpty", function(string) {
  if (!string) {
    return "-";
  }
  return string;
});

Vue.filter("longTrackName", function(string) {
  const long = window.__GBGB_GLOBAL__.trackNamesFilters.find(value => {
    return value.replace === string;
  });
  if (long) {
    return long.with;
  }
  return string;
});

window.vm = new Vue({
  el: "#app",
  components: {},
});

// jQuery-based initialisations
$(() => {
  new Hero();
  new HeaderNav();
  new BackToTop();
  new Modal();

  new BlockAnimations();
});
