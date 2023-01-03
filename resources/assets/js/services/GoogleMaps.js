class GoogleMaps {
  static loadGoogleMapsAPI() {
    window.gmapsCallback = () => GoogleMaps.loaded(window.google);
    const script = document.createElement("script");
    const apiKey = window.__GBGB_GLOBAL__.googleMapsApiKey;
    window.__GBGB_GLOBAL__.googleMapsApiIsLoading = true;
    script.async = true;
    script.defer = true;
    script.src =
    `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=gmapsCallback&libraries=places&language=en`;
    document.querySelector("head").appendChild(script);
  }
  static loaded() {
    const event = new CustomEvent("gmaps-api-loaded", {});
    document.dispatchEvent(event);
  }
  static getGoogleMapsAPIObject(callback) {
    if (window.google && window.google.maps) {
      callback(window.google.maps);
    }
    if (!window.__GBGB_GLOBAL__.googleMapsApiIsLoading) {
      GoogleMaps.loadGoogleMapsAPI();
    }
    document.addEventListener("gmaps-api-loaded", () => {
      callback(window.google.maps);
    });
  }
}

export default GoogleMaps;
