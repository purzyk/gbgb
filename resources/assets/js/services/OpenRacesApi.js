import axios from "axios";

const apiUrl = window.__GBGB_GLOBAL__.openRaces.apiUrl;

class OpenRacesApi {
  static getOpenRaces(page, itemsPerPage, filters = []) {
    const params = {};
    params.page = page && page !== null ? page : 1;
    params.pagesize = itemsPerPage && itemsPerPage !== null ? itemsPerPage : 5;
    filters.forEach(element => {
      if (element.value) {
        params[element.name] = element.value;
      }
    });
    return axios
      .get(apiUrl + "OpenRaces", { params })
      .then(res => {
        return res.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
}

export default OpenRacesApi;
