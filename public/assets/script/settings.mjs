import { Popup } from "/assets/script/popup.mjs";

// noinspection JSUnusedGlobalSymbols
/**
 * @param {string} language
 */
export default function init(language) {
  let languageArray = JSON.parse(language);

  class BookingItemTypePopup extends Popup {
    /**
     * @param {HTMLElement} popup
     * @param {HTMLElement} button
     */
    constructor(popup, button) {
      super(popup, button);
      this.bookingItemTypeFieldContainer = this.popup.querySelector(
        "#bookingItemTypeFields",
      );
      this.#extendedInit();
    }

    #extendedInit() {
      this.popup.querySelector("#addBookingItemTypeField").addEventListener(
        "click",
        function () {
          this.#addBookingItemTypeField();
        }.bind(this),
      );
    }

    #addBookingItemTypeField() {
      let fieldContainer = document.createElement("div");
      fieldContainer.classList.add("field");

      /*Field name*/
      let fieldNameContainer = document.createElement("div");
      fieldNameContainer.classList.add("fieldName");

      let fieldNameLabel = document.createElement("label");
      fieldNameLabel.textContent = languageArray["fieldName"];

      let fieldName = document.createElement("input");
      fieldName.type = "text";
      fieldName.name = "fieldName[]";

      fieldNameContainer.append(fieldNameLabel, fieldName);

      /*Field type*/
      let fieldTypeContainer = document.createElement("div");
      fieldTypeContainer.classList.add("fieldType");

      let fieldTypeLabel = document.createElement("label");
      fieldTypeLabel.textContent = languageArray["fieldType"];

      let fieldType = document.createElement("select");
      fieldType.name = "fieldType[]";
      let options = [
        { value: "text", text: languageArray["fieldTypeOptions"][0] },
        { value: "number", text: languageArray["fieldTypeOptions"][1] },
        { value: "date", text: languageArray["fieldTypeOptions"][2] },
      ];
      options.forEach((option) => {
        let element = document.createElement("option");
        element.value = option.value;
        element.textContent = option.text;
        fieldType.appendChild(element);
      });

      fieldTypeContainer.append(fieldTypeLabel, fieldType);

      let removeFieldButton = document.createElement("button");
      removeFieldButton.textContent = languageArray["removeField"];
      removeFieldButton.addEventListener("click", function () {
        fieldContainer.remove();
      });

      fieldContainer.append(
        fieldNameContainer,
        fieldTypeContainer,
        removeFieldButton,
      );
      this.bookingItemTypeFieldContainer.append(fieldContainer);
    }
  }

  new BookingItemTypePopup(
    document.getElementById("createBookingItemTypePopup"),
    document.getElementById("createBookingItemType"),
  );
}
