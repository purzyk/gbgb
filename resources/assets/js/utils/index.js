import $ from "jquery";

class Utils {
  static getViewportHeight() {
    return Math.min(
      window.innerHeight || Infinity,
      window.screen.innerHeight || Infinity,
      window.height || Infinity,
      window.screen.availHeight || Infinity
    );
  }

  static uuid(length = 8) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghiklmnopqrstuvwxyz".split(
      ""
    );

    if (!length) {
      length = Math.floor(Math.random() * chars.length);
    }

    let str = "";
    for (let i = 0; i < length; i++) {
      str += chars[Math.floor(Math.random() * chars.length)];
    }
    return str;
  }

  static getTranslation(key) {
    return window.__GBGB_GLOBAL__.translations[key];
  }

  static getWaypointOffset() {
    return Utils.isMobile() ? "100%" : "80%";
  }

  static getDistanceHaversine(lat1, lng1, lat2, lng2) {
    const dLat = Utils.deg2rad(lat2 - lat1);
    const dLng = Utils.deg2rad(lng2 - lng1);
    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(Utils.deg2rad(lat1)) *
        Math.cos(Utils.deg2rad(lat2)) *
        Math.sin(dLng / 2) *
        Math.sin(dLng / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return 3958.75587 * c;
  }

  static deg2rad(deg) {
    return deg * (Math.PI / 180);
  }

  static isMobile() {
    var check = false;
    /* eslint-disable */
    (function(a) {
      if (
        /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
          a
        ) ||
        /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
          a.substr(0, 4)
        )
      )
        check = true;
    })(navigator.userAgent || navigator.vendor || window.opera);
    return check;
    /* eslint-enable */
  }

  static buildUrl(baseUrl, parameters) {
    if (baseUrl[0] !== "h") {
      baseUrl = window.location.protocol + baseUrl;
    }
    let url = baseUrl;
    let index = 0;
    for (const key in parameters) {
      let value = parameters[key];
      if (value) {
        if (index === 0) {
          url += "?" + key + "=" + encodeURIComponent(value);
        } else {
          url += "&" + key + "=" + encodeURIComponent(value);
        }
        index += 1;
      }
    }
    return url;
  }

  static downloadFile(filename, text, mime = "text/plain") {
    var blob = new Blob([text], {
      type: "text/plain;charset=utf-8;",
    });

    if (navigator.msSaveBlob) {
      navigator.msSaveBlob(blob, filename);
      return;
    }

    let element = document.createElement("a");
    element.setAttribute(
      "href",
      "data:" + mime + ";charset=utf-8," + encodeURIComponent(text)
    );
    element.setAttribute("download", filename);

    element.style.display = "none";
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
  }

  static showNotification(content) {
    let event = new CustomEvent("showNotification", {
      detail: {
        content: content,
      },
    });
    document.dispatchEvent(event);
  }

  static getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  static inView(
    item,
    transitionTypeClass,
    callbackFunction = function() {},
    offset = "10%"
  ) {
    let $item = $(item);
    $item.addClass(transitionTypeClass);
    $item.addClass("Transition--hidden");
    return $item.viewportChecker({
      offset,
      callbackFunction,
      classToRemove: "Transition--hidden",
    });
  }

  static getIEVersion() {
    var sAgent = window.navigator.userAgent;
    var Idx = sAgent.indexOf("MSIE");

    // If IE, return version number.
    if (Idx > 0)
      return parseInt(sAgent.substring(Idx + 5, sAgent.indexOf(".", Idx)));
    // If IE 11 then look for Updated user agent string.
    else if (navigator.userAgent.match(/Trident\/7\./)) return 11;
    else return 0; //It is not IE
  }

  static decimalToFraction(decimal) {
    if (decimal === 0) {
      return "";
    }
    const gcd = function(a, b) {
      if (!b) return a;
      a = parseInt(a);
      b = parseInt(b);
      return gcd(b, a % b);
    };

    const len = decimal.toString().length - 2;

    let denominator = Math.pow(10, len);
    let numerator = decimal * denominator;

    const divisor = gcd(numerator, denominator);

    numerator /= divisor;
    denominator /= divisor;

    return numerator.toFixed() + "/" + denominator.toFixed();
  }

  static roundDecimals(value, decimals) {
    return Number(Math.round(value + "e" + decimals) + "e-" + decimals);
  }
}

export default Utils;
