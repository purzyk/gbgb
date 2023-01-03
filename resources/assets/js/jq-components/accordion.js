class Accordion {
  constructor() {
    const accordion = document.getElementsByClassName("Publications__button");

    for (let i = 0; i < accordion.length; i++) {
      accordion[i].addEventListener("click", function() {
        /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */
        this.classList.toggle("Active");

        /* Toggle between hiding and showing the active panel */
        let panel = this.nextElementSibling;
        if (panel.style.display === "block") {
          panel.style.display = "none";
        } else {
          panel.style.display = "block";
        }
      });
    }
  }
}

export default Accordion;
