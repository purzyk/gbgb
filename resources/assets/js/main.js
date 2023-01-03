import $ from "jquery";
window.$ = $;
window.jQuery = $;
import "./lib/viewport";
import Vue from "vue";

import { polyfill } from "es6-promise";
polyfill();
import Polyfills from "./utils/polyfills";
Polyfills.polyfillAll();

import Vue2TouchEvents from "vue2-touch-events";
Vue.use(Vue2TouchEvents);

import GoogleMap from "./components/GoogleMap";

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

import RaceCourseResults from "./components/RaceCourseResults";
import RaceCoursesBlock from "./components/RaceCoursesBlock";
import RaceCourse from "./components/RaceCourse";

import MobileNav from "./components/mobile-nav/MobileNav";
import MobileNavSubsection from "./components/mobile-nav/MobileNavSubsection";
import MobileNavMore from "./components/mobile-nav/MobileNavMore";

import LatestNews from "./components/latest-news/LatestNews";
import LatestNewsFilters from "./components/latest-news/LatestNewsFilters";
import LatestNewsFilterSelect from "./components/latest-news/LatestNewsFilterSelect";
import LatestNewsItem from "./components/latest-news/LatestNewsItem";

import Meeting from "./components/meeting/Meeting";
import MeetingFilter from "./components/meeting/MeetingFilter";
import MeetingRace from "./components/meeting/MeetingRace";
import MeetingRaceTrap from "./components/meeting/MeetingRaceTrap";

import Commitment from "./components/Commitment";

import Greyhound from "./components/greyhound/Greyhound";
import GreyhoundFilters from "./components/greyhound/GreyhoundFilters";
import GreyhoundTable from "./components/greyhound/GreyhoundTable";
import GreyhoundRow from "./components/greyhound/GreyhoundRow";

import TrainersList from "./components/trainers-list/TrainersList";
import TrainersListTable from "./components/trainers-list/TrainersListTable";
import TrainersListRow from "./components/trainers-list/TrainersListRow";
import TrainersListFilters from "./components/trainers-list/TrainersListFilters";

import BigRaceWinners from "./components/big-race-winners/BigRaceWinners";
import BigRaceWinnersTable from "./components/big-race-winners/BigRaceWinnersTable";
import BigRaceWinnersRow from "./components/big-race-winners/BigRaceWinnersRow";
import BigRaceWinnersFilters from "./components/big-race-winners/BigRaceWinnersFilters";

import OpenRaces from "./components/open-races/OpenRaces";
import OpenRacesFilters from "./components/open-races/OpenRacesFilters";
import OpenRacesTable from "./components/open-races/OpenRacesTable";
import OpenRacesRow from "./components/open-races/OpenRacesRow";

import MyKennelTopHeader from "./components/my-kennel/MyKennelTopHeader";
import MyKennelWelcome from "./components/my-kennel/MyKennelWelcome";
import MyKennelLogin from "./components/my-kennel/MyKennelLogin";
import MyKennelLogout from "./components/my-kennel/MyKennelLogout";
import MyKennelChangepass from "./components/my-kennel/MyKennelChangepass";
import MyKennelRegister from "./components/my-kennel/MyKennelRegister";
import MyKennelDogs from "./components/my-kennel/MyKennelDogs";
import MyDogsRow from "./components/my-kennel/MyDogsRow";
import MyKennelCourses from "./components/my-kennel/MyKennelCourses";
import MyKennelResources from "./components/my-kennel/MyKennelResources";
import MyKennelUpload from "./components/my-kennel/MyKennelUpload";
import MyKennelLostPass from "./components/my-kennel/MyKennelLostPass";
import MyKennelResetPass from "./components/my-kennel/MyKennelResetPass";

import FindAGreyhound from "./components/FindAGreyhound";
import PopupLogin from "./components/PopupLogin";
import PopupDelete from "./components/PopupDelete";
import Notifications from "./components/Notifications";
import ResourceSet from "./components/ResourceSet";

import OurDogs from "./components/OurDogs";
import OurDogsPost from "./components/OurDogsPost";

Vue.component("google-map", GoogleMap);

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

Vue.component("race-course-results", RaceCourseResults);
Vue.component("race-courses-block", RaceCoursesBlock);
Vue.component("race-course", RaceCourse);

Vue.component("mobile-nav", MobileNav);
Vue.component("mobile-nav-subsection", MobileNavSubsection);
Vue.component("mobile-nav-more", MobileNavMore);

Vue.component("latest-news", LatestNews);
Vue.component("latest-news-filters", LatestNewsFilters);
Vue.component("latest-news-filter-select", LatestNewsFilterSelect);
Vue.component("latest-news-item", LatestNewsItem);

Vue.component("meeting", Meeting);
Vue.component("meeting-filter", MeetingFilter);
Vue.component("meeting-race", MeetingRace);
Vue.component("meeting-race-trap", MeetingRaceTrap);

Vue.component("commitment", Commitment);

Vue.component("greyhound", Greyhound);
Vue.component("greyhound-filters", GreyhoundFilters);
Vue.component("greyhound-table", GreyhoundTable);
Vue.component("greyhound-row", GreyhoundRow);

Vue.component("trainers-list", TrainersList);
Vue.component("trainers-list-table", TrainersListTable);
Vue.component("trainers-list-row", TrainersListRow);
Vue.component("trainers-list-filter", TrainersListFilters);

Vue.component("big-race-winners", BigRaceWinners);
Vue.component("big-race-winners-table", BigRaceWinnersTable);
Vue.component("big-race-winners-row", BigRaceWinnersRow);
Vue.component("big-race-winners-filter", BigRaceWinnersFilters);

Vue.component("open-races", OpenRaces);
Vue.component("open-races-filters", OpenRacesFilters);
Vue.component("open-races-table", OpenRacesTable);
Vue.component("open-races-row", OpenRacesRow);

Vue.component("mykennel-top-header", MyKennelTopHeader);
Vue.component("mykennel-welcome", MyKennelWelcome);
Vue.component("mykennel-login", MyKennelLogin);
Vue.component("mykennel-logout", MyKennelLogout);
Vue.component("mykennel-changepass", MyKennelChangepass);
Vue.component("mykennel-register", MyKennelRegister);
Vue.component("mykennel-dogs", MyKennelDogs);
Vue.component("my-dogs-row", MyDogsRow);
Vue.component("mykennel-courses", MyKennelCourses);
Vue.component("mykennel-resources", MyKennelResources);
Vue.component("mykennel-upload", MyKennelUpload);
Vue.component("mykennel-lostpass", MyKennelLostPass);
Vue.component("mykennel-resetpass", MyKennelResetPass);

Vue.component("find-a-greyhound", FindAGreyhound);
Vue.component("popup-login", PopupLogin);
Vue.component("popup-delete", PopupDelete);
Vue.component("notifications", Notifications);
Vue.component("resource-set", ResourceSet);

Vue.component("our-dogs", OurDogs);
Vue.component("our-dogs-post", OurDogsPost);

import Hero from "./jq-components/hero";
import HeaderNav from "./jq-components/header-nav";
import ShareLinks from "./jq-components/share-links";
import Accordion from "./jq-components/accordion";
import BoardMembers from "./jq-components/board-members";
import Testimonials from "./jq-components/testimonials";
import Forms from "./jq-components/forms";
import BackToTop from "./jq-components/backToTop";
import Modal from "./jq-components/modal";
import VideoCarousel from "./jq-components/video-carousel";

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
  new Accordion();
  new Hero();
  new HeaderNav();
  new ShareLinks();
  new BoardMembers();
  new Testimonials();
  new Forms();
  new BackToTop();
  new Modal();
  new VideoCarousel();

  new BlockAnimations();
});
