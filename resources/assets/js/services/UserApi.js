import axios from "axios";

const apiUrl = window.__GBGB_GLOBAL__.ajaxUrl;

class UserApi {
  static logIn(username, password, nonce) {
    let form_data = new FormData();
    form_data.append("action", "gbgb_login");
    form_data.append("gbgb_username", username);
    form_data.append("gbgb_password", password);
    form_data.append("security", nonce);
    return new Promise((resolve, reject) => {
      axios
        .post(apiUrl, form_data)
        .then(res => {
          resolve(res.data.data);
        })
        .catch(e => {
          console.log(e);
          reject();
        });
    });
  }

  static logInWithStadiumPortal(username, password, nonce) {
    let form_data = new FormData();
    form_data.append("action", "gbgb_login");
    form_data.append("gbgb_username", username);
    form_data.append("gbgb_password", password);
    form_data.append("security", nonce);
    return new Promise((resolve, reject) => {
      axios
        .post(apiUrl, form_data)
        .then(res => {
          if (res.data.data.data.stadium_login) {
            axios
              .get(res.data.data.data.stadium_login_url, { headers: { username: username, password: password } })
              .finally(() => {
                resolve(res.data.data);
              });
          } else {
            resolve(res.data.data);
          }
        })
        .catch(e => {
          console.log(e);
          reject();
        });
    });
  }

  static logOut(nonce) {
    let form_data = new FormData();
    form_data.append("action", "gbgb_logout");
    form_data.append("security", nonce);
    return axios
      .post(apiUrl, form_data)
      .then(res => {
        return res.data.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static lostPass(username, nonce) {
    let form_data = new FormData();
    form_data.append("action", "gbgb_lost_pass");
    form_data.append("gbgb_username", username);
    form_data.append("security", nonce);
    return axios
      .post(apiUrl, form_data)
      .then(res => {
        return res.data.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static resetPass(token, password, nonce) {
    let form_data = new FormData();
    form_data.append("action", "gbgb_reset_pass");
    form_data.append("gbgb_token", token);
    form_data.append("gbgb_password", password);
    form_data.append("security", nonce);
    return axios
      .post(apiUrl, form_data)
      .then(res => {
        return res.data.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static changePass(old_password, password, confirm_password, nonce) {
    let form_data = new FormData();
    form_data.append("action", "gbgb_change_pass");
    form_data.append("gbgb_old_password", old_password);
    form_data.append("gbgb_password", password);
    form_data.append("gbgb_confirm_password", confirm_password);
    form_data.append("security", nonce);
    return axios
      .post(apiUrl, form_data)
      .then(res => {
        return res.data.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
  static register(username, password, confirm_password, first_name, last_name, nonce) {
    let form_data = new FormData();
    form_data.append("action", "gbgb_register");
    form_data.append("gbgb_username", username);
    form_data.append("gbgb_password", password);
    form_data.append("gbgb_confirm_password", confirm_password);
    form_data.append("gbgb_first_name", first_name);
    form_data.append("gbgb_last_name", last_name);
    form_data.append("security", nonce);
    return axios
      .post(apiUrl, form_data)
      .then(res => {
        return res.data.data;
      })
      .catch(e => {
        console.log(e);
      });
  }
}

export default UserApi;
