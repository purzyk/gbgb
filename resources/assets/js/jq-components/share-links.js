import $ from "jquery";

class ShareLinks {
  constructor() {
    const $shareLinks = $(".ShareLink");
    if (!$shareLinks.length) return;
    $shareLinks.on("click", this.openNewWindow);
  }

  openNewWindow(e) {
    e.preventDefault();
    window.open($(this).attr("href"), "Share", "width=600,height=450");
  }
}

export default ShareLinks;
