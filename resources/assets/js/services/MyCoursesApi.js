import axios from "axios";

axios.defaults.baseURL = "/wp-json/wp/v2/";

class MyCoursesApi {
  static getCourses(nonce) {
    return axios
      .get("mycourses", {
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res;
      });
  }
  static addCourse(nonce, courseId) {
    let form_data = new FormData();
    form_data.append("courseId", courseId);
    return axios
      .post("mycourses/add", form_data, {
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res;
      });
  }
  static removeCourse(nonce, courseId) {
    const params = {};
    params.courseId = courseId && courseId !== null ? courseId : 0;
    return axios
      .delete("mycourses/remove", {
        params: params,
        headers: {
          "X-WP-Nonce": nonce,
        },
      })
      .then(res => {
        return res;
      });
  }
}

export default MyCoursesApi;
