import axios from "axios";

axios.defaults.baseURL = "/wp-json/wp/v2/";

class ApiService {
  static getPosts(params) {
    return axios.get("posts", { params }).then(res => {
      return res;
    });
  }

  static getOurDogs(pageId, page, perPage) {
    const params = {};
    params.pageId = pageId && pageId !== null ? pageId : [];
    params.perPage = perPage && perPage !== null ? perPage : -1;
    params.page = page && page !== null ? page : 1;
    return axios
      .get("ourdogs", { params })
      .then(res => {
        return res;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static getSocialMediaFeed(postIds, feedSlug, perPage, page) {
    const params = {};
    params.postIds = postIds && postIds !== null ? postIds : [];
    params.feedSlug = feedSlug && feedSlug !== null ? feedSlug : null;
    params.perPage = perPage && perPage !== null ? perPage : -1;
    params.page = page && page !== null ? page : 1;
    return axios
      .get("social-media-feed", { params })
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static getRaceCourses() {
    return axios
      .get("race-courses", {})
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static getBigRaceWinners(page, itemsPerPage, filters) {
    const params = {};
    params.page = page && page !== null ? page : 1;
    params.itemsPerPage =
      itemsPerPage && itemsPerPage !== null ? itemsPerPage : 5;
    filters.forEach(element => {
      if (element.value) {
        params[element.name] = element.value;
      }
    });
    return axios
      .get("big-race-winners", { params })
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static getBigRaceWinnersAutocompletes() {
    return axios
      .get("big-race-winners/autocompletes", {})
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static getTrainers(page, itemsPerPage, filters) {
    const params = {};
    params.page = page && page !== null ? page : 1;
    params.itemsPerPage =
      itemsPerPage && itemsPerPage !== null ? itemsPerPage : 5;
    filters.forEach(element => {
      if (element.value) {
        params[element.name] = element.value;
      }
    });
    return axios
      .get("trainers-list", { params })
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static getTrainersAutocompletes() {
    return axios
      .get("trainers-list/autocompletes", {})
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static createOwnerPhoto(nonce, mediaId, raceName, petName) {
    let form_data = new FormData();
    form_data.append("mediaId", mediaId);
    form_data.append("raceName", raceName);
    form_data.append("petName", petName);
    return axios
      .post("ownerphotos/add", form_data, {
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }

  static getSearchAutocomplete() {
    return axios
      .get("searchautocompletes", {})
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
}

export default ApiService;
