import axios from "axios";

axios.defaults.baseURL = "/wp-json/wp/v2/";

class MyDogsApi {
  static getDogs(nonce) {
    return axios
      .get("mydogs", {
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res;
      });
  }
  static addDog(nonce, dogId) {
    let form_data = new FormData();
    form_data.append("dogId", dogId);
    return axios
      .post("mydogs/add", form_data, {
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res;
      });
  }
  static removeDog(nonce, dogId) {
    const params = {};
    params.dogId = dogId && dogId !== null ? dogId : 0;
    return axios
      .delete("mydogs/remove", {
        params: params,
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res;
      });
  }
  static editDog(nonce, dogId, notes, notification) {
    let form_data = new FormData();
    form_data.append("notes", notes);
    form_data.append("notification", notification);
    form_data.append("dogId", dogId);
    return axios
      .post("mydogs/edit", form_data, {
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res;
      });
  }
}

export default MyDogsApi;
