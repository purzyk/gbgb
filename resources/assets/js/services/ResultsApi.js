import axios from "axios";

const apiUrl = window.__GBGB_GLOBAL__.resultsApiUrl;

class ResultsApi {
  static getLatestResults(page, itemsPerPage, filters = []) {
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
      .get(apiUrl + "results", { params })
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getGreyhoundResults(dogId, page, itemsPerPage, filters = []) {
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
      .get(apiUrl + "results/dog/" + dogId, { params })
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getGreyhound(dogId) {
    return axios
      .get(apiUrl + "dogs/" + dogId)
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getGreyhoundFixtures(dogId) {
    return axios
      .get(apiUrl + "dogs/" + dogId + "/fixtures")
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getGreyhoundClasses(dogId) {
    return axios
      .get(apiUrl + "dogs/" + dogId + "/classes")
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getGreyhoundDistances(dogId) {
    return axios
      .get(apiUrl + "dogs/" + dogId + "/distances")
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getTrackNames() {
    return axios
      .get(apiUrl + "results/tracks-names", {})
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getClasses() {
    return axios
      .get(apiUrl + "results/race-classes", {})
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getGreyhoundAutocomplete(input) {
    if (typeof input !== "string" || input.length < 3) {
      return [];
    }
    return axios
      .get(apiUrl + "dogs/autocomplete/" + input, {})
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static getMeeting(meetingId) {
    if (meetingId && meetingId !== null) {
      const params = {};
      params.meeting = meetingId;

      return axios
        .get(apiUrl + "results/meeting/" + meetingId, { params })
        .then(res => {
          return res.data;
        })
        .catch(e => {
          console.log(e);
        });
    }
  }
  static getRace(raceId) {
    if (raceId && raceId !== null) {
      const params = {};
      params.id = raceId;

      return axios
        .get(apiUrl + "results/race/" + raceId, { params })
        .then(res => {
          return res.data;
        })
        .catch(e => {
          console.log(e);
        });
    }
  }
}

export default ResultsApi;
