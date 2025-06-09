export class Popup {
  /**
   * @param {HTMLElement} popup
   * @param {HTMLElement|array} triggers
   */
  constructor(popup, triggers) {
    this.popup = popup;
    this.triggers = triggers;
    this.#init();
  }

  #init() {
    if (!this.popup || !this.triggers) {
      return;
    }
    if (!Array.isArray(this.triggers)) {
      this.triggers = [this.triggers];
    }

    this.popup.classList.add("initialized");

    this.triggers.forEach((trigger) => {
      trigger.addEventListener("click", () => {
        this.open();
      });
    });

    this.popup.querySelector(".close").addEventListener("click", () => {
      this.close();
    });

    this.popup.addEventListener("click", (event) => {
      if (event.target === this.popup) {
        this.close();
      }
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        this.close();
      }
    });
  }

  open() {
    this.popup.classList.add("active");
  }

  close() {
    this.popup.classList.remove("active");
  }
}
